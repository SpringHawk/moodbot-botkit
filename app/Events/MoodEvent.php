<?php

namespace App\Events;

use App\Mood;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MoodEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $moodValue;

    /**
     * Create a new event instance.
     *
     * @param Mood $moodValue
     */
    public function __construct(Mood $moodValue)
    {
        $this->moodValue = $moodValue;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [];
    }
}
