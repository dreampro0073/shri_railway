<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class OfflineSyncIdempotency
{
    public function handle(Request $request, Closure $next)
    {
        $offlineUuid = $request->header('X-Offline-Id');

        if (!$offlineUuid) {
            return $next($request);
        }

        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $offlineUuid)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid offline request ID.',
            ], 422);
        }

        $user = Auth::user();
        $deviceUuid = strtolower((string) $request->header('X-Offline-Device'));
        $offlineUserId = (int) $request->header('X-Offline-User', $user ? $user->id : 0);

        if ($request->header('X-Offline-Token')) {
            $device = DB::table('offline_devices')
                ->where('device_uuid', $deviceUuid)
                ->where('user_id', $offlineUserId)
                ->where('status', 1)
                ->first();

            $offlineUser = User::where('id', $offlineUserId)->where('active', 1)->first();
            if (!$device || !$offlineUser || (int) $device->client_id !== (int) $offlineUser->client_id || !Hash::check($request->header('X-Offline-Token'), $device->token_hash)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Offline device access is invalid or has been revoked.',
                ], 403);
            }

            Auth::setUser($offlineUser);
            $user = $offlineUser;
        }

        if (!$user || !$user->client_id) {
            return response()->json([
                'success' => false,
                'message' => 'Client account was not found.',
            ], 401);
        }

        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $deviceUuid)) {
            return response()->json([
                'success' => false,
                'message' => 'Offline device ID is missing or invalid.',
            ], 422);
        }

        $clientId = (int) $user->client_id;
        $service = substr($request->path(), 0, 80);
        $requestHash = hash('sha256', $request->method().'|'.$request->path().'|'.$request->getContent());

        $userId = (int) $user->id;

        return DB::transaction(function () use ($request, $next, $offlineUuid, $clientId, $service, $requestHash, $userId, $deviceUuid) {
            $syncRequest = DB::table('offline_sync_requests')
                ->where('client_id', $clientId)
                ->where('offline_uuid', $offlineUuid)
                ->lockForUpdate()
                ->first();

            if ($syncRequest && !hash_equals($syncRequest->request_hash, $requestHash)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This offline request ID was already used for a different bill.',
                ], 409);
            }

            if ($syncRequest && ((int) $syncRequest->user_id !== $userId || $syncRequest->device_uuid !== $deviceUuid)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Offline request belongs to a different operator or PC.',
                ], 409);
            }

            if ($syncRequest && (int) $syncRequest->status === 1 && $syncRequest->response_json) {
                return response($syncRequest->response_json, 200)
                    ->header('Content-Type', 'application/json');
            }

            if (!$syncRequest) {
                DB::table('offline_sync_requests')->insertOrIgnore([
                    'client_id' => $clientId,
                    'user_id' => $userId,
                    'device_uuid' => $deviceUuid,
                    'offline_uuid' => $offlineUuid,
                    'offline_bill_number' => 'OFF-'.strtoupper(substr(str_replace('-', '', $offlineUuid), 0, 16)),
                    'service' => $service,
                    'request_hash' => $requestHash,
                    'status' => 0,
                    'created_at' => now(),
                ]);

                $syncRequest = DB::table('offline_sync_requests')
                    ->where('client_id', $clientId)
                    ->where('offline_uuid', $offlineUuid)
                    ->lockForUpdate()
                    ->first();

                if (!$syncRequest || !hash_equals($syncRequest->request_hash, $requestHash) || (int) $syncRequest->user_id !== $userId || $syncRequest->device_uuid !== $deviceUuid) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This offline request ID was already used for a different bill.',
                    ], 409);
                }

                if ((int) $syncRequest->status === 1 && $syncRequest->response_json) {
                    return response($syncRequest->response_json, 200)
                        ->header('Content-Type', 'application/json');
                }
            }

            $response = $next($request);
            $responseData = json_decode($response->getContent(), true);

            if ($response->isSuccessful() && is_array($responseData) && !empty($responseData['success'])) {
                DB::table('offline_sync_requests')
                    ->where('id', $syncRequest->id)
                    ->update([
                        'status' => 1,
                        'response_json' => $response->getContent(),
                        'updated_at' => now(),
                    ]);
                DB::table('offline_devices')
                    ->where('client_id', $clientId)
                    ->where('user_id', $userId)
                    ->where('device_uuid', $deviceUuid)
                    ->update(['last_sync_at' => now()]);
            } else {
                DB::table('offline_sync_requests')->where('id', $syncRequest->id)->delete();
            }

            return $response;
        });
    }
}
