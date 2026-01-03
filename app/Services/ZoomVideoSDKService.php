<?php

namespace App\Services;

use Firebase\JWT\JWT;

class ZoomVideoSDKService
{
    public static function generateToken(
        string $sessionName,
        string $userIdentity,
        int $roleType
    ): string {
        $payload = [
            'app_key' => config('services.zoom.sdk_key'),
            'iat' => time(),
            'exp' => time() + 7200, // Extended to 2 hours
            'tpc' => $sessionName,
            'user_identity' => $userIdentity,
            'role_type' => $roleType,
            'cloud_recording_option' => 1, // Enable cloud recording
            'cloud_recording_election' => 1, // Allow host to decide who records
        ];

        return JWT::encode(
            $payload,
            config('services.zoom.sdk_secret'),
            'HS256'
        );
    }
}
