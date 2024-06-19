<?php

use App\Library\VoiceRSS;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/text-to-speech', function () {

    $tts = new VoiceRSS("de27525cb2f840c6b0c2637972583c63");

    $content = $tts
        ->language('en-us')
        ->text("Welcome to yor home")
        ->rate(0)
        ->toSpeech();

    Storage::disk('public')->put('test.mp3', $content);

    return response($content, 200, [
        'Content-Type' => 'audio/mp3',
        'Content-Dispostion'    => 'inline'
    ]);
});
