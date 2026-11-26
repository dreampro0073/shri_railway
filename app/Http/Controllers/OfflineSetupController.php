<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;

class OfflineSetupController extends Controller
{
    public function index()
    {
        return view('admin.offline_setup', [
            'sidebar' => '',
            'subsidebar' => '',
        ]);
    }

    public function registerDevice(Request $request)
    {
        $request->validate([
            'device_uuid' => ['required', 'uuid'],
            'device_name' => ['nullable', 'string', 'max:120'],
            'offline_token' => ['nullable', 'string', 'max:120'],
        ]);

        $user = Auth::user();
        if (!$user || !$user->client_id || (int) $user->active !== 1) {
            return Response::json([
                'success' => false,
                'message' => 'Active client user login is required.',
            ], 403);
        }

        $deviceUuid = strtolower($request->device_uuid);
        $device = DB::table('offline_devices')
            ->where('client_id', $user->client_id)
            ->where('user_id', $user->id)
            ->where('device_uuid', $deviceUuid)
            ->first();

        $offlineToken = $device && $request->offline_token && Hash::check($request->offline_token, $device->token_hash)
            ? $request->offline_token
            : Str::random(64);

        $values = [
            'device_name' => $request->device_name,
            'status' => 1,
            'updated_at' => now(),
        ];

        if ($device) {
            if (!$request->offline_token || !Hash::check($request->offline_token, $device->token_hash)) {
                $values['token_hash'] = Hash::make($offlineToken);
            }
            DB::table('offline_devices')->where('id', $device->id)->update($values);
        } else {
            $values['client_id'] = $user->client_id;
            $values['user_id'] = $user->id;
            $values['device_uuid'] = $deviceUuid;
            $values['token_hash'] = Hash::make($offlineToken);
            $values['created_at'] = now();
            DB::table('offline_devices')->insert($values);
        }

        $serviceIds = DB::table('client_services')
            ->where('client_id', $user->client_id)
            ->where('status', 1)
            ->pluck('services_id')
            ->map(function ($serviceId) {
                return (int) $serviceId;
            })
            ->values();

        return Response::json([
            'success' => true,
            'offline_token' => $offlineToken,
            'user' => [
                'id' => (int) $user->id,
                'client_id' => (int) $user->client_id,
                'name' => $user->name,
                'priv' => (int) $user->priv,
                'service_ids' => $serviceIds,
            ],
            'device_uuid' => $deviceUuid,
        ], 200, []);
    }
}
