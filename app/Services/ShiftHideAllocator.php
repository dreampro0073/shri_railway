
<?php

namespace App\Services;

use App\Models\Entry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShiftHideAllocator
{
    public static function apply(
        $data,
        &$rows,
        $clientId,
        $date,
        $userId,
        $serviceIds
    ) {
        // Priv 3 staff: No hide amount
        if (
            !Auth::check() ||
            !in_array((int) Auth::user()->priv, [2, 5], true)
        ) {
            return $data;
        }

        // Hide amount in whole rupees, stored as cents
        // Example: Rs 1000 => 100000 cents
        $hideRupees = (int) round(
            max(0, (float) Entry::hideAmount($clientId, $date))
        );

        $hideCents = $hideRupees * 100;

        if ($hideCents <= 0) {
            return $data;
        }

        // Only these services are eligible
        $eligibleLabels = [
            'Sitting',
            'CloakRoom',
            'Pods',
            'Single Cabins',
            'Double Beds',
            'Scanning'
        ];

        // Total eligible cash for selected report
        $eligibleRowsCashCents = 0;

        foreach ($rows as $row) {

            if (
                in_array(
                    $row['label'] ?? '',
                    $eligibleLabels,
                    true
                )
            ) {
                $eligibleRowsCashCents += (int) round(
                    max(
                        0,
                        (float) ($row['total_shift_cash'] ?? 0)
                    ) * 100
                );
            }
        }

        if ($eligibleRowsCashCents <= 0) {
            return $data;
        }

        // All staff / overall report
        if ((int) $userId === 0) {

            $deductionCents = min(
                $hideCents,
                $eligibleRowsCashCents
            );

        } else {

            // Staff-wise eligible cash
            $cashByStaff = self::cashByStaff(
                $clientId,
                $date,
                $serviceIds
            );

            $totalCashCents = array_sum($cashByStaff);

            if ($totalCashCents <= 0) {
                return $data;
            }

            // Never hide more than available cash
            $hideCents = min(
                $hideCents,
                $totalCashCents
            );

            // Full rupees only
            $hideWholeRupees = intdiv($hideCents, 100);

            // Consistent staff order
            ksort($cashByStaff, SORT_NUMERIC);

            $previousCash = 0;
            $deductionCents = 0;

            foreach ($cashByStaff as $staffId => $cashCents) {

                // Cumulative allocation ensures
                // total matches exactly
                $before = intdiv(
                    $hideWholeRupees * $previousCash,
                    $totalCashCents
                );

                $previousCash += $cashCents;

                $after = intdiv(
                    $hideWholeRupees * $previousCash,
                    $totalCashCents
                );

                if ((int) $staffId === (int) $userId) {

                    // Whole-rupee hide allocation
                    $deductionCents = ($after - $before) * 100;

                    break;
                }
            }

            // Avoid negative cash
            $deductionCents = min(
                $deductionCents,
                $eligibleRowsCashCents
            );
        }

        // Apply allocated hide amount to eligible rows
        foreach ($rows as &$row) {

            if ($deductionCents <= 0) {
                break;
            }

            if (
                !in_array(
                    $row['label'] ?? '',
                    $eligibleLabels,
                    true
                )
            ) {
                continue;
            }

            $rowCashCents = (int) round(
                max(
                    0,
                    (float) ($row['total_shift_cash'] ?? 0)
                ) * 100
            );

            $deduct = min(
                $rowCashCents,
                $deductionCents
            );

            $deductAmount = $deduct / 100;

            $row['total_shift_cash'] =
                ($rowCashCents - $deduct) / 100;

            $row['total_collection'] =
                (float) $row['total_collection'] - $deductAmount;

            $data['total_shift_cash'] =
                (float) $data['total_shift_cash'] - $deductAmount;

            $data['total_collection'] =
                (float) $data['total_collection'] - $deductAmount;

            $deductionCents -= $deduct;
        }

        unset($row);

        return $data;
    }

    private static function cashByStaff(
        $clientId,
        $date,
        $serviceIds
    ) {
        $tables = [];

        // Sitting
        if (in_array(1, $serviceIds)) {
            $tables[] = [
                'sitting_entries',
                null,
                null
            ];

            $tables[] = [
                'e_entries',
                'is_collected',
                'not1'
            ];
        }

        // Cloakroom
        if (in_array(2, $serviceIds)) {
            $tables[] = [
                'cloakroom_entries',
                null,
                null
            ];

            $tables[] = [
                'cloakroom_penalities',
                'is_collected',
                'not1'
            ];
        }

        // Room
        if (in_array(8, $serviceIds)) {

            $roomTypes = in_array(
                (int) $clientId,
                [18, 19]
            ) ? [1] : [1, 2, 3];

            $tables[] = [
                'room_entries',
                'type',
                $roomTypes
            ];

            $tables[] = [
                'room_e_entries',
                'room_extra',
                $roomTypes
            ];
        }

        // Scanning
        if (in_array(9, $serviceIds)) {
            $tables[] = [
                'scanning_entries',
                null,
                null
            ];
        }

        $amounts = [];

        foreach ($tables as $entry) {

            list($table, $filter, $value) = $entry;

            $query = DB::table($table)
                ->select('added_by')
                ->selectRaw('SUM(paid_amount) as cash')
                ->where('client_id', $clientId)
                ->where('date', $date)
                ->where('pay_type', 1);

            if ($filter === 'is_collected') {
                $query->where('is_collected', '!=', 1);
            }

            if ($filter === 'type') {
                $query->whereIn('type', $value);
            }

            if ($filter === 'room_extra') {
                $query->whereIn('type', $value)
                      ->where('status', 0);
            }

            $results = $query
                ->groupBy('added_by')
                ->get();

            foreach ($results as $result) {

                $id = (int) $result->added_by;

                $cashCents = (int) round(
                    (float) $result->cash * 100
                );

                $amounts[$id] =
                    ($amounts[$id] ?? 0) + $cashCents;
            }
        }

        return $amounts;
    }
}
