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

        $question = Question::create("Hey! I hope you're having a great day! Do you want to tell me how you feel?")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Yes, sure!')->value('yes'),
                Button::create('No, sorry')->value('no'),
            ]);

        return $this->ask($question, function (Answer $answer) use ($mood) {

            $question = Question::create('Nice! So, how are you?')
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
                            $this->say("Great to hear that you are happy :)");
                        } else if ($answer->getValue() === '4') {
                            $this->say("Awesome, stay smooth!");
                        } else if ($answer->getValue() === '3') {
                            $this->say("Thanks for letting me know!");
                        } else if ($answer->getValue() === '2') {
                            $this->say("When I get bored at work I sometimes start working to pass the time.");
                        } else if ($answer->getValue() === '1') {
                            $this->say("Oh no! I hope you cheer up, soon.");
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
                $this->say("Ahh, okay. I'll ask you again, tomorrow. Have a nice day!");
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