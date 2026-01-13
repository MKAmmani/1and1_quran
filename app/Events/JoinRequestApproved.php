<?php

namespace App\Events;

use App\Models\JoinRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JoinRequestApproved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $joinRequest;

    /**
     * Create a new event instance.
     */
    public function __construct(JoinRequest $joinRequest)
    {
        $this->joinRequest = $joinRequest;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('student.' . $this->joinRequest->student_id),
        ];
    }

    public function broadcastAs()
    {
        return 'join.request.approved';
    }

    public function broadcastWith()
    {
        $data = [
            'joinRequest' => [
                'id' => $this->joinRequest->id,
                'student_id' => $this->joinRequest->student_id,
                'live_session_id' => $this->joinRequest->live_session_id,
                'status' => $this->joinRequest->status,
            ]
        ];

        // Debug logging
        \Log::info('JoinRequestApproved event broadcast data', $data);

        return $data;
    }
}
