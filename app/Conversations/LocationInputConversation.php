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

        $question = Question::create("Hey! Would you like to tell me where you're going to be working, today?")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('1st Floor')->value('0'),
                Button::create('3nd Floor')->value('1'),
                Button::create('Home Office')->value('2'),
                Button::create("Not Available")->value('3'),
            ]);

        return $this->ask($question, function (Answer $answer) use ($location) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() === '0' or $answer->getValue() === '1') {
                    $this->say('Cool! Happy to see you, here!');
                } else {
                    $this->say('Thanks for letting me know!');
                }
            }
            $user = $this->bot->getUser();
            $dbUser = Location::where('user_id', $user->getId())->first();
            $user_location = $answer->getValue();
            $id = $user->getId();
            $username = $user->getUsername();
            $realname = $user->getInfo()['real_name'];
            if ($dbUser === NULL) {
                $location->name = $realname;
                $location->user_name = $username;
                $location->user_id = $id;
                $location->user_location = $user_location;
                $location->save();
            } else {
                $dbUser->update(['user_location' => $user_location, 'name' => $realname]);
            }

            //$this->bot->userStorage()->delete('mood_value');
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