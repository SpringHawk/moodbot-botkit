<?php

namespace App\Console\Commands;

use App\Conversations\ExampleConversation;
use App\Conversations\MoodInputConversation;
use App\Http\Controllers\BotManController;
use App\Mood;
use BotMan\Drivers\Slack\SlackDriver;
use BotMan\BotMan\BotMan;
use Illuminate\Console\Command;

class AskForMoodInput extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:mood';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call the scheduler every day.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $botman = app('botman');
        $botman->loadDriver('Slack');
        $response = $botman->sendRequest('users.list');
        $users = json_decode($response->getContent(), true);
        $userID = collect($users['members'])->pluck('profile.email', 'id')->filter()->flip()->all();

        //$botman->say('Hello dud', 'U9G1JEG03', SlackDriver::class);

        foreach($userID as $key => $value)
        {
            $botman->startConversation(new MoodInputConversation(), $value, SlackDriver::class);

        }
    }
}
