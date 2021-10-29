<?php

use App\Http\Controllers\Api\TriviaGameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('trivia-game/create', [TriviaGameController::class, 'create']);
Route::get('trivia-game/{id}', [TriviaGameController::class, 'get']);
Route::post('trivia-game/submit-answer/{answer}', [TriviaGameController::class, 'submitAnswer']);