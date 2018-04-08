<?php

namespace App\Listeners;

use App\Events\MoodEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MoodEvenListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MoodEvent  $event
     * @return void
     */
    public function handle(MoodEvent $event)
    {
        $event->moodValue;
    }
}
