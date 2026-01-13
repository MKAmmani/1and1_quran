<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageSent;
use App\Models\ChatMessage;
use App\Models\LiveSession;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ChatMessageController extends Controller
{
    /**
     * Store a newly created chat message in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'live_session_id' => 'required|exists:live_sessions,id',
                'message' => 'required|string|max:1000|min:1',
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Check if user is authorized to participate in this session
            $liveSession = LiveSession::findOrFail($validated['live_session_id']);

            $isAuthorized = ($liveSession->teacher_id == $user->id) ||
                           $liveSession->participants()->where('user_id', $user->id)->exists();

            if (!$isAuthorized) {
                return response()->json(['error' => 'Forbidden: Not authorized to send messages in this session'], 403);
            }

            $chatMessage = ChatMessage::create([
                'live_session_id' => $validated['live_session_id'],
                'user_id' => $user->id,
                'message' => trim($validated['message']),
            ]);

            // Load user relationship before broadcasting
            $chatMessage->load('user');

            // Broadcast the message to the live session channel
            event(new ChatMessageSent($chatMessage));

            return response()->json($chatMessage, 201);

        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error storing chat message: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'session_id' => $request->live_session_id ?? null,
                'error' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Get chat messages for a specific live session.
     */
    public function index(Request $request, LiveSession $liveSession): JsonResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Check if user is authorized to access this session
            $isAuthorized = ($liveSession->teacher_id == $user->id) ||
                           $liveSession->participants()->where('user_id', $user->id)->exists();

            if (!$isAuthorized) {
                return response()->json(['error' => 'Forbidden: Not authorized to access this session'], 403);
            }

            $messages = $liveSession->chatMessages()
                ->with('user:id,first_name,last_name,email')
                ->latest()
                ->paginate(20);

            return response()->json($messages);

        } catch (\Exception $e) {
            Log::error('Error fetching chat messages: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'session_id' => $liveSession->id ?? null,
                'error' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
