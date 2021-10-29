<?php

namespace App\Http\Controllers;

class GameController extends Controller
{
    public function __invoke()
    {
        return view('trivia-game');
    }
}
