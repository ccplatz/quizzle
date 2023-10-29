<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('/questions', QuestionController::class)->except(['show']);

    Route::post('quizzes/store', [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('quizzes/{quiz}/next_question', [QuizController::class, 'nextQuestion'])->name('quizzes.next');
    Route::get('quizzes/{quiz}/result', [QuizController::class, 'result'])->name('quizzes.result');
    Route::post('quizzes/{quiz}/store_choices', [QuizController::class, 'storeChoices'])->name('quizzes.store-choices');
});