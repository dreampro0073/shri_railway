<?php

namespace App\Services;

use App\Models\Entry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShiftHideAllocatorOld
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

        /*
        |--------------------------------------------------------------------------
        | FIX: Normalize date format
        |--------------------------------------------------------------------------
        | Example:
        | 22-10-2026 => 2026-10-22
        | 2026-10-22 => 2026-10-22
        */

        if ($date instanceof \DateTimeInterface) {

            $date = $date->format('Y-m-d');

        } else {

            $date = trim((string) $date);

            if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $date)) {

                $parsedDate = \DateTime::createFromFormat(
                    '!d-m-Y',
                    $date
                );

                if (
                    !$parsedDate ||
                    $parsedDate->format('d-m-Y') !== $date
                ) {
                    throw new \InvalidArgumentException(
                        'Invalid shift date.'
                    );
                }

                $date = $parsedDate->format('Y-m-d');

            } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {

                $parsedDate = \DateTime::createFromFormat(
                    '!Y-m-d',
                    $date
                );

                if (
                    !$parsedDate ||
                    $parsedDate->format('Y-m-d') !== $date
                ) {
                    throw new \InvalidArgumentException(
                        'Invalid shift date.'
                    );
                }

            } else {

                throw new \InvalidArgumentException(
                    'Invalid shift date format.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Hide amount calculation
        |--------------------------------------------------------------------------
        */

        $hideRupees = (int) round(
            max(
                0,
                (float) Entry::hideAmount($clientId, $date)
            )
        );

        // Convert rupees to cents
        $hideCents = $hideRupees * 100;

        if ($hideCents <= 0) {
            return $data;
        }

        /*
        |--------------------------------------------------------------------------
        | Eligible services
        |--------------------------------------------------------------------------
        */

        $eligibleLabels = [
            'Sitting',
            'CloakRoom',
            'Pods',
            'Single Cabins',
            'Double Beds',
            'Scanning'
        ];

        /*
        |--------------------------------------------------------------------------
        | Calculate eligible cash
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Overall report
        |--------------------------------------------------------------------------
        */

        if ((int) $userId === 0) {

            $deductionCents = min(
                $hideCents,
                $eligibleRowsCashCents
            );

        } else {

            /*
            |--------------------------------------------------------------------------
            | Staff-wise cash calculation
            |--------------------------------------------------------------------------
            */

            $cashByStaff = self::cashByStaff(
                $clientId,
                $date,
                $serviceIds
            );

            $totalCashCents = array_sum($cashByStaff);

            if ($totalCashCents <= 0) {
                return $data;
            }

            // Hide cannot exceed available cash
            $hideCents = min(
                $hideCents,
                $totalCashCents
            );

            // Allocate only whole rupees
            $hideWholeRupees = intdiv(
                $hideCents,
                100
            );

            // Consistent staff ordering
            ksort($cashByStaff, SORT_NUMERIC);

            $previousCash = 0;
            $deductionCents = 0;

            foreach ($cashByStaff as $staffId => $cashCents) {

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

                    $deductionCents = (
                        $after - $before
                    ) * 100;

                    break;
                }
            }

            // Avoid negative cash
            $deductionCents = min(
                $deductionCents,
                $eligibleRowsCashCents
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Apply deduction only on eligible services
        |--------------------------------------------------------------------------
        */

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

            // Update service cash
            $row['total_shift_cash'] = (
                $rowCashCents - $deduct
            ) / 100;

            // Update service collection
            $row['total_collection'] =
                (float) $row['total_collection']
                - $deductAmount;

            // Update grand cash
            $data['total_shift_cash'] =
                (float) $data['total_shift_cash']
                - $deductAmount;

            // Update grand collection
            $data['total_collection'] =
                (float) $data['total_collection']
                - $deductAmount;

            $deductionCents -= $deduct;
        }

        unset($row);

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | Staff-wise cash collection
    |--------------------------------------------------------------------------
    */

    private static function cashByStaff(
        $clientId,
        $date,
        $serviceIds
    ) {

        $tables = [];

        /*
        |--------------------------------------------------------------------------
        | Sitting
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Cloakroom
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Room
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Scanning
        |--------------------------------------------------------------------------
        */

        if (in_array(9, $serviceIds)) {

            $tables[] = [
                'scanning_entries',
                null,
                null
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Get actual cash of each staff
        |--------------------------------------------------------------------------
        */

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

                $query->where(
                    'is_collected',
                    '!=',
                    1
                );
            }

            if ($filter === 'type') {

                $query->whereIn(
                    'type',
                    $value
                );
            }

            if ($filter === 'room_extra') {

                $query->whereIn(
                    'type',
                    $value
                )->where('status', 0);
            }

            $results = $query
                ->groupBy('added_by')
                ->get();

            foreach ($results as $result) {

                $id = (int) $result->added_by;

                $cashCents = (int) round(
                    (float) $result->cash * 100
                );

                $amounts[$id] = (
                    $amounts[$id] ?? 0
                ) + $cashCents;
            }
        }

        return $amounts;
    }
}
