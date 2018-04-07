<?php

namespace App\Http\Controllers;

use App\Conversations\ExampleConversation;
use App\Conversations\MoodInputConversation;
use App\Mood;
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
    public function startConversation(BotMan $bot)
    {

        $bot->startConversation(new MoodInputConversation());
        $user = $bot->getUser();
        $id = $user->getId();
        $username = $user->getUsername();
        $mood = new Mood;
        $mood->user_name = $username;
        $mood->user_id = $id;
        $mood->mood_value = $bot->userStorage()->get('moodValue');;
        $mood->save();
    }
}
