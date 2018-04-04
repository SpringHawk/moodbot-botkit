<?php
/**
 * Created by PhpStorm.
 * User: coroi
 * Date: 4/3/2018
 * Time: 8:36 PM
 */

namespace App\Conversations;


use App\Mood;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class MoodInputConversation extends Conversation
{
    /**
     * First question
     */
    public function askMood()
    {



        return $this->ask('Would you like to tell me about your mood today?', function (Answer $response) {

            $question = Question::create('How is your mood at work today?')
                ->fallback('Unable to ask question')
                ->callbackId('ask_reason')
                ->addButtons([
                    Button::create('Happy :grin:')->value('5'),
                    Button::create('Neutral :neutral_face:')->value('4'),
                    Button::create('Sick :face_with_thermometer:')->value('3'),
                    Button::create('Angry :angry:')->value('2'),
                    Button::create('Sad :pensive:')->value('1'),
                    Button::create('Unamused :unamused:')->value('3'),
                ]);

            if ($response->getText() === 'yes') {
                $this->ask($question, function (Answer $answer) {
                    if ($answer->isInteractiveMessageReply()) {
                        if ($answer->getValue() === '5') {
                            $this->say("Wohoo. It's good to hear that you are happy");
                        }
                        else if ($answer->getValue() === '4') {
                            $this->say("I am sure we can cheer you up a bit. Have a random Chuck Norris joke");
                            $joke = json_decode(file_get_contents('http://api.icndb.com/jokes/random'));
                            $this->say($joke->value->joke);
                        }
                        else if ($answer->getValue() === '3') {
                            $this->say("Oh noo. We hope that you will get well soon! :worried:");
                        }
                        else if ($answer->getValue() === '2') {
                            $this->say("Thank you for your input. Try to breathe deeply a few times, I'm sure you will feel better");
                        }
                        else if ($answer->getValue() === '1') {
                            $this->say("We are sad to hear that. Would you like to talk about it?");
                        }
                        else {
                            $this->say("I did not understand that");
                        }
                        $mood = new Mood;
                        $mood->mood_value = $answer->getValue();
                        $mood->user_name = 'David';
                        $mood->user_id = '12345';
                        $mood->save();
                    }
                });
            } else {
                $this->say('Ahh, okay. We can try tomorrow then. Have a nice day!');
            }
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