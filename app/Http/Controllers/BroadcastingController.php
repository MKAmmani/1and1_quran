<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BroadcastingController extends Controller
{
    /**
     * Authenticate the request for broadcasting.
     */
    public function authenticate(Request $request): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated.'], 403);
        }

        $channelName = $request->channel_name;

        // For private channels, verify the user can access the channel
        if (str_starts_with($channelName, 'private-')) {
            // Extract the model and ID from the channel name (e.g., private-live-session.123)
            $channelParts = explode('.', $channelName);
            if (count($channelParts) >= 2) {
                $modelInfo = $channelParts[0];
                $id = $channelParts[1];

                // For live-session channels, check if user is participant or teacher
                if (str_contains($modelInfo, 'live-session')) {
                    $liveSessionId = $id;

                    // Check if user is teacher of this session or a participant
                    $user = auth()->user();

                    $liveSession = \App\Models\LiveSession::find($liveSessionId);
                    if (!$liveSession) {
                        return response()->json(['error' => 'Session not found.'], 404);
                    }

                    // Allow teacher or participants to join
                    $canJoin = ($user->id === $liveSession->teacher_id) ||
                              $liveSession->participants()->where('user_id', $user->id)->exists();

                    if (!$canJoin) {
                        return response()->json(['error' => 'Unauthorized.'], 403);
                    }
                }
            }
        }

        return $this->validAuthenticationResponse($request, auth()->user());
    }

    /**
     * Return the valid authentication response.
     */
    protected function validAuthenticationResponse(Request $request, $user): JsonResponse
    {
        $channelName = $request->channel_name;

        if (str_starts_with($channelName, 'private-')) {
            return response()->json([
                'channel_data' => [
                    'user_id' => $user->id,
                    'user_info' => [
                        'id' => $user->id,
                        'name' => $user->first_name . ' ' . $user->last_name,
                        'email' => $user->email,
                    ],
                ],
            ]);
        }

        return response()->json(['status' => 'authenticated']);
    }
}
