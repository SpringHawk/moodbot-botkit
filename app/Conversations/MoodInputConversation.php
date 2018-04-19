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
use Illuminate\Support\Facades\Log;

class MoodInputConversation extends Conversation
{
    /**
     * First question
     */


    public function askMood()
    {

        $mood = new Mood;

        $question = Question::create("Hello, would you like to tell me about your mood today?")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Yes, sure!')->value('yes'),
                Button::create('No')->value('no'),
            ]);

        return $this->ask($question, function (Answer $answer) use ($mood) {

            $question = Question::create('How are you feeling today?')
                ->fallback('Unable to ask question')
                ->callbackId('ask_reason')
                ->addButtons([
                    Button::create('Happy :grin:')->value('5'),
                    Button::create('Relaxed :relaxed:')->value('4'),
                    Button::create('Okay :slightly_smiling_face:')->value('3'),
                    Button::create('Bored :unamused:')->value('2'),
                    Button::create('Angry :angry:')->value('1'),
                ]);

            if ($answer->getValue() === 'yes' or $answer->getText() === 'yes') {
                $this->ask($question, function (Answer $answer) use ($mood) {
                    if ($answer->isInteractiveMessageReply()) {
                        if ($answer->getValue() === '5') {
                            $this->say("It's good to hear that you are happy");
                        } else if ($answer->getValue() === '4') {
                            $this->say("I am sure we can cheer you up a bit. Have a random Chuck Norris joke");
                            $joke = json_decode(file_get_contents('http://api.icndb.com/jokes/random'));
                            $this->say($joke->value->joke);
                        } else if ($answer->getValue() === '3') {
                            $this->say("Oh noo. We hope that you will get well soon! :worried:");
                        } else if ($answer->getValue() === '2') {
                            $this->say("Thank you for your input. I would suggest you to take a break");
                        } else if ($answer->getValue() === '1') {
                            $this->say("We are sad to hear that. Would you like to talk about it?");
                        } else {
                            $this->say("I did not understand that");
                        }

                    }
                    //Log::info('This should be first!');
                    $moodValue = $answer->getValue();
                    // Happy
                    if ($moodValue === '5') {
                        $mood->valence = '0.177';
                        $mood->arousal = '0.508';
                    // Relaxed
                    } else if ($moodValue === '4') {
                        $mood->valence = '0.237';
                        $mood->arousal = '0.172';
                    // Okay
                    } else if ($moodValue === '3') {
                        $mood->valence = '0.5';
                        $mood->arousal = '0.5';
                    // Bored
                    } else if ($moodValue === '2') {
                        $mood->valence = '0.583';
                        $mood->arousal = '0.404';
                    //Angry
                    } else {
                        $mood->valence = '0.75';
                        $mood->arousal = '0.806';
                    }
                    $user = $this->bot->getUser();
                    $id = $user->getId();
                    $username = $user->getUsername();
                    $mood->user_name = $username;
                    $mood->user_id = $id;
                    $mood->save();
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