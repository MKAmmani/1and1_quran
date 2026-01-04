<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuranVerseChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $liveSessionId;
    public int $surah;
    public int $ayah;
    public string $surahName;

    /**
     * Create a new event instance.
     */
    public function __construct(int $liveSessionId, int $surah, int $ayah, string $surahName)
    {
        $this->liveSessionId = $liveSessionId;
        $this->surah = $surah;
        $this->ayah = $ayah;
        $this->surahName = $surahName;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('live-session.' . $this->liveSessionId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'quran.verse.changed';
    }
}