<?php

namespace App\Services;

use App\Models\Entry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Hide only one client/day amount from eligible cash collections.
 * Staff shares are proportional to collected eligible cash, with deterministic
 * cent-level rounding by ascending added_by ID.
 */
class ShiftHideAllocator
{
    public static function apply($data, &$rows, $clientId, $date, $userId, $serviceIds)
    {
        if (!Auth::check() || !in_array((int) Auth::user()->priv, [2, 5], true)) {
            return $data;
        }

        $hideCents = (int) round(max(0, (float) Entry::hideAmount($clientId, $date)) * 100);
        if ($hideCents <= 0) return $data;

        $eligibleLabels = ['Sitting', 'CloakRoom', 'Pods', 'Single Cabins', 'Double Beds', 'Scanning'];
        $eligibleRowsCashCents = 0;
        foreach ($rows as $row) {
            if (in_array($row['label'] ?? '', $eligibleLabels, true)) {
                $eligibleRowsCashCents += (int) round(max(0, (float) ($row['total_shift_cash'] ?? 0)) * 100);
            }
        }
        if ($eligibleRowsCashCents <= 0) return $data;

        if ((int) $userId === 0) {
            $deductionCents = min($hideCents, $eligibleRowsCashCents);
        } else {
            $cashByStaff = self::cashByStaff($clientId, $date, $serviceIds);
            $totalCashCents = array_sum($cashByStaff);
            if ($totalCashCents <= 0) return $data;
            $hideCents = min($hideCents, $totalCashCents);
            ksort($cashByStaff, SORT_NUMERIC);
            $previousCash = 0;
            $deductionCents = 0;
            foreach ($cashByStaff as $staffId => $cashCents) {
                $before = intdiv($hideCents * $previousCash, $totalCashCents);
                $previousCash += $cashCents;
                $after = intdiv($hideCents * $previousCash, $totalCashCents);
                if ((int) $staffId === (int) $userId) {
                    $deductionCents = $after - $before;
                    break;
                }
            }
            $deductionCents = min($deductionCents, $eligibleRowsCashCents);
        }

        foreach ($rows as &$row) {
            if ($deductionCents <= 0) break;
            if (!in_array($row['label'] ?? '', $eligibleLabels, true)) continue;
            $rowCashCents = (int) round(max(0, (float) ($row['total_shift_cash'] ?? 0)) * 100);
            $deduct = min($rowCashCents, $deductionCents);
            $row['total_shift_cash'] = ($rowCashCents - $deduct) / 100;
            $row['total_collection'] = (float) $row['total_collection'] - $deduct / 100;
            $data['total_shift_cash'] = (float) $data['total_shift_cash'] - $deduct / 100;
            $data['total_collection'] = (float) $data['total_collection'] - $deduct / 100;
            $deductionCents -= $deduct;
        }
        unset($row);
        return $data;
    }

    private static function cashByStaff($clientId, $date, $serviceIds)
    {
        $tables = [];
        if (in_array(1, $serviceIds)) {
            $tables[] = ['sitting_entries', null, null];
            $tables[] = ['e_entries', 'is_collected', 'not1'];
        }
        if (in_array(2, $serviceIds)) {
            $tables[] = ['cloakroom_entries', null, null];
            $tables[] = ['cloakroom_penalities', 'is_collected', 'not1'];
        }
        if (in_array(8, $serviceIds)) {
            $roomTypes = in_array((int) $clientId, [18, 19]) ? [1] : [1, 2, 3];
            $tables[] = ['room_entries', 'type', $roomTypes];
            $tables[] = ['room_e_entries', 'room_extra', $roomTypes];
        }
        if (in_array(9, $serviceIds)) {
            $tables[] = ['scanning_entries', null, null];
        }
        $amounts = [];
        foreach ($tables as $entry) {
            list($table, $filter, $value) = $entry;
            $query = DB::table($table)->select('added_by')
                ->selectRaw('SUM(paid_amount) as cash')
                ->where('client_id', $clientId)->where('date', $date)->where('pay_type', 1);
            if ($filter === 'is_collected') $query->where('is_collected', '!=', 1);
            if ($filter === 'type') $query->whereIn('type', $value);
            if ($filter === 'room_extra') $query->whereIn('type', $value)->where('status', 0);
            foreach ($query->groupBy('added_by')->get() as $result) {
                $id = (int) $result->added_by;
                $amounts[$id] = ($amounts[$id] ?? 0) + (int) round((float) $result->cash * 100);
            }
        }
        return $amounts;
    }
}
