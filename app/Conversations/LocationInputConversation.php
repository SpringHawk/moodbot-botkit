<?php
/**
 * Created by PhpStorm.
 * User: coroi
 * Date: 4/3/2018
 * Time: 8:36 PM
 */

namespace App\Conversations;



use App\Location;
use App\Mood;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Facades\Log;

class LocationInputConversation extends Conversation
{
    /**
     * First question
     */


    public function askMood()
    {
        $location = new Location;

        $question = Question::create("Please let me know where will you work from today.")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('1st Floor')->value('1floor'),
                Button::create('2nd Floor')->value('2floor'),
                Button::create('Home Office')->value('home'),
                Button::create("I'm on vacation")->value('vacation'),
            ]);

        return $this->ask($question, function (Answer $answer) use ($location) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() === '1floor' or $answer->getValue() === '2floor') {
                    $this->say('Okay, we are happy you are here!');
                } else {
                    $this->say('Thank you for your input!');
                }
            }
            $user_location = $answer->getValue();
            $user = $this->bot->getUser();
            $id = $user->getId();
            $username = $user->getUsername();
            $location->user_name = $username;
            $location->user_id = $id;
            $location->user_location = $user_location;
            //$this->bot->userStorage()->delete('mood_value');
            $location->save();
        });
    }
    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askMood();
    }
}