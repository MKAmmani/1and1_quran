<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageSent;
use App\Models\ChatMessage;
use App\Models\LiveSession;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatMessageController extends Controller
{
    /**
     * Store a newly created chat message in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'live_session_id' => 'required|exists:live_sessions,id',
            'message' => 'required|string|max:1000',
        ]);

        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $liveSession = \App\Models\LiveSession::findOrFail($request->live_session_id);

        // Allow authenticated users to send messages to live sessions
        // Broadcasting channels will handle real-time access control

        $chatMessage = ChatMessage::create([
            'live_session_id' => $request->live_session_id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // Broadcast the message to the live session channel
        event(new ChatMessageSent($chatMessage));

        return response()->json($chatMessage->load('user'));
    }

    /**
     * Get chat messages for a specific live session.
     */
    public function index(Request $request, LiveSession $liveSession): JsonResponse
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Allow authenticated users to access chat messages
        // Broadcasting channels will handle real-time access control

        $messages = $liveSession->chatMessages()
            ->with('user')
            ->latest()
            ->paginate(20); // Paginate the results

        return response()->json($messages);
    }
}
