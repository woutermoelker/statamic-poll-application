<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::statamic('example', 'example-view', [
//    'title' => 'Example'
// ]);
Route::get('/api/polls/get', [\App\Http\Controllers\PollController::class, 'getQuestionsWithOptions'])->name('getQuestionsWithOptions');
Route::post('/api/option/{option}/{action}', [\App\Http\Controllers\OptionController::class, 'updateVotes'])
    ->name('updateVotes')
    ->where('action', 'increment|decrement');

Route::get('/api/questions/{question}', [QuestionController::class, 'show'])->name('question.show');

Route::post('/api/vote/{question}/{option}', [VoteController::class, 'store'])->name('vote.store');


Route::post('/api/poll/create', [\App\Http\Controllers\PollController::class, 'createPoll'])->name('poll.crate');
Route::post('/api/poll/{question}/update', [\App\Http\Controllers\PollController::class, 'updatePoll'])->name('poll.update');
Route::delete('/api/poll/{question}/delete', [\App\Http\Controllers\PollController::class, 'deletePollWithOptionAndVotes'])->name('poll.delete');
