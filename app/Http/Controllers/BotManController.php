<?php

namespace App\Http\Controllers;

use App\Conversations\ExampleConversation;
use App\Conversations\LocationInputConversation;
use App\Conversations\MoodInputConversation;
use App\Mood;
use App\MyFiles\IntermediaryData;
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');
        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startMoodConversation(BotMan $bot)
    {

        $bot->startConversation(new MoodInputConversation());
    }

    public function startLocationConversation(BotMan $bot)
    {

        $bot->startConversation(new LocationInputConversation());
    }
}
