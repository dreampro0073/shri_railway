
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use App\Models\Entry;
use App\Models\CloakRoom;
use App\Models\Sitting;
use App\Models\Canteen;
use App\Models\Massage;
use App\Models\Locker;
use App\Models\Recliner;
use App\Models\Room;
use App\Models\Rest;
use App\Models\ScanningEntry;

class Shift extends Model
{
    /**
     * Apply hide amount only to the permitted service row.
     */
    private static function applyHideAmountToRow($row, &$hide_amount_remaining)
    {
        if ($hide_amount_remaining <= 0) {
            return $row;
        }

        $cash = max(0, (float) ($row['total_shift_cash'] ?? 0));

        $deduction = min($cash, $hide_amount_remaining);

        $row['total_shift_cash'] = $cash - $deduction;

        $row['total_collection'] = max(
            0,
            (float) ($row['total_collection'] ?? 0) - $deduction
        );

        $hide_amount_remaining -= $deduction;

        return $row;
    }


    /**
     * Get combined shift status.
     */
    public static function getStatus($request = null, $client_id = 0, $service_ids = [])
    {
        $input_date = isset($request['input_date'])
            ? $request['input_date']
            : date('Y-m-d');

        // Privilege 2 and 5: combined data of all users.
        if (in_array(Auth::user()->priv, [2, 5])) {
            $user_id = 0;
        } else {
            $user_id = Auth::id();
        }

        Entry::checkShift();

        $data = [
            'total_shift_upi' => 0,
            'total_shift_cash' => 0,
            'total_shift_dues' => 0,
            'total_collection' => 0,
            'last_hour_upi_total' => 0,
            'last_hour_cash_total' => 0,
            'last_hour_dues_total' => 0,
            'last_hour_total' => 0,
        ];

        /*
         * Fetch hide amount only once.
         * It will be deducted only from:
         * Sitting, CloakRoom, Room and Scanning.
         */
        $hide_amount_remaining = 0;

        if (in_array(Auth::user()->priv, [2, 5])) {
            $hide_amount_remaining = max(
                0,
                (float) Entry::hideAmount($client_id, $input_date)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Sitting - Service ID 1
        |--------------------------------------------------------------------------
        */

        if (in_array(1, $service_ids)) {

            $sitting_data = Sitting::totalShiftData(
                $input_date,
                $user_id,
                $client_id,
                false
            );

            $sitting_data = self::applyHideAmountToRow(
                $sitting_data,
                $hide_amount_remaining
            );

            $data = self::calculateAmount($sitting_data, $data);
        }


        /*
        |--------------------------------------------------------------------------
        | Cloak Room - Service ID 2
        |--------------------------------------------------------------------------
        */

        if (in_array(2, $service_ids)) {

            $cloak_data = CloakRoom::totalShiftData(
                $input_date,
                $user_id,
                $client_id,
                false
            );

            $cloak_data = self::applyHideAmountToRow(
                $cloak_data,
                $hide_amount_remaining
            );

            $data = self::calculateAmount($cloak_data, $data);
        }


        /*
        |--------------------------------------------------------------------------
        | Canteen - Service ID 3
        |--------------------------------------------------------------------------
        */

        if (in_array(3, $service_ids)) {

            $canteen_data = Canteen::totalShiftData(
                $input_date,
                $user_id,
                $client_id
            );

            $data = self::calculateAmount($canteen_data, $data);
        }


        /*
        |--------------------------------------------------------------------------
        | Massage - Service ID 4
        |--------------------------------------------------------------------------
        */

        if (in_array(4, $service_ids)) {

            $massage_data = Massage::totalShiftData(
                $input_date,
                $user_id,
                $client_id
            );

            $data = self::calculateAmount($massage_data, $data);
        }


        /*
        |--------------------------------------------------------------------------
        | Locker - Service ID 5
        |--------------------------------------------------------------------------
        */

        if (in_array(5, $service_ids)) {

            $locker_data = Locker::totalShiftData(
                $input_date,
                $user_id,
                $client_id
            );

            $data = self::calculateAmount($locker_data, $data);
        }


        /*
        |--------------------------------------------------------------------------
        | Recliner - Service ID 7
        |--------------------------------------------------------------------------
        */

        if (in_array(7, $service_ids)) {

            $recliner_data = Recliner::totalShiftData(
                $input_date,
                $user_id,
                $client_id
            );

            $data = self::calculateAmount($recliner_data, $data);
        }


        /*
        |--------------------------------------------------------------------------
        | Room - Service ID 8
        |--------------------------------------------------------------------------
        */

        if (in_array(8, $service_ids)) {

            // Pod
            $pod_data = Room::totalShiftData(
                1,
                $input_date,
                $user_id,
                $client_id,
                false
            );

            $pod_data = self::applyHideAmountToRow(
                $pod_data,
                $hide_amount_remaining
            );

            $data = self::calculateAmount($pod_data, $data);


            // Single Cabin
            $singal_cabin_data = Room::totalShiftData(
                2,
                $input_date,
                $user_id,
                $client_id,
                false
            );

            $singal_cabin_data = self::applyHideAmountToRow(
                $singal_cabin_data,
                $hide_amount_remaining
            );

            $data = self::calculateAmount($singal_cabin_data, $data);


            // Double Bed
            $double_bed_data = Room::totalShiftData(
                3,
                $input_date,
                $user_id,
                $client_id,
                false
            );

            $double_bed_data = self::applyHideAmountToRow(
                $double_bed_data,
                $hide_amount_remaining
            );

            $data = self::calculateAmount($double_bed_data, $data);
        }


        /*
        |--------------------------------------------------------------------------
        | Scanning - Service ID 9
        |--------------------------------------------------------------------------
        */

        if (in_array(9, $service_ids)) {

            $scanning_data = ScanningEntry::totalShiftData(
                $input_date,
                $user_id,
                $client_id,
                false
            );

            $scanning_data = self::applyHideAmountToRow(
                $scanning_data,
                $hide_amount_remaining
            );

            $data = self::calculateAmount($scanning_data, $data);
        }


        /*
        |--------------------------------------------------------------------------
        | Rest - Service ID 10
        |--------------------------------------------------------------------------
        */

        if (in_array(10, $service_ids)) {

            $rest_data = Rest::totalShiftData(
                $input_date,
                $user_id,
                $client_id
            );

            $data = self::calculateAmount($rest_data, $data);
        }

        return $data;
    }


    /**
     * Compatibility methods.
     * Both use the corrected common calculation.
     */
    public static function getStatusNew($request = null, $client_id = 0, $service_ids = [])
    {
        return self::getStatus($request, $client_id, $service_ids);
    }

    public static function getStatusOld($request = null, $client_id = 0, $service_ids = [])
    {
        return self::getStatus($request, $client_id, $service_ids);
    }


    /**
     * Calculate combined shift totals.
     */
    public static function calculateAmount($total_data, $data)
    {
        $data['total_shift_upi'] += $total_data['total_shift_upi'] ?? 0;

        $data['total_shift_cash'] += $total_data['total_shift_cash'] ?? 0;

        $data['total_shift_dues'] += $total_data['total_shift_dues'] ?? 0;

        $data['total_collection'] += $total_data['total_collection'] ?? 0;

        $data['last_hour_upi_total'] += $total_data['last_hour_upi_total'] ?? 0;

        $data['last_hour_cash_total'] += $total_data['last_hour_cash_total'] ?? 0;

        $data['last_hour_dues_total'] += $total_data['last_hour_dues_total'] ?? 0;

        $data['last_hour_total'] += $total_data['last_hour_total'] ?? 0;

        return $data;
    }
}
