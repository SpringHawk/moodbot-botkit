<?php
use App\Http\Controllers\BotManController;
use BotMan\BotMan\BotMan;

$botman = resolve('botman');

//$botman->hears('hi', function (Botman $bot) {
//    $bot->reply('Hello!');
//});

$botman->hears('hi|hello|mood|hello there', BotManController::class.'@startMoodConversation');

$botman->hears('location', BotManController::class.'@startLocationConversation');