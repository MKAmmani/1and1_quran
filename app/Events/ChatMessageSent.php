<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatMessage;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatMessage $chatMessage)
    {
        $this->chatMessage = $chatMessage->load(['user']); // Load user data with the message
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('live-session.' . $this->chatMessage->live_session_id),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'chat.message';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'chatMessage' => [
                'id' => $this->chatMessage->id,
                'live_session_id' => $this->chatMessage->live_session_id,
                'user_id' => $this->chatMessage->user_id,
                'message' => $this->chatMessage->message,
                'created_at' => $this->chatMessage->created_at->toISOString(),
                'user' => $this->chatMessage->user ? [
                    'id' => $this->chatMessage->user->id,
                    'first_name' => $this->chatMessage->user->first_name,
                    'last_name' => $this->chatMessage->user->last_name,
                    'name' => $this->chatMessage->user->first_name . ' ' . $this->chatMessage->user->last_name,
                    'email' => $this->chatMessage->user->email,
                    'role' => $this->chatMessage->user->role,
                ] : null,
            ],
        ];
    }
}
