<?php

use App\Http\Controllers\ExamController;
use App\Http\Controllers\ResultController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});




Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/exam', [ExamController::class, 'index'])->name('exam');
    Route::post('/saveResult', [ResultController::class, 'store'])->name('saveResult');
    Route::get('/exam/{exam}', [ExamController::class, 'show'])->name('exams.show');
    Route::get('/exam/results/{exam_id}', [ResultController::class, 'index'])->name('showResults');
});
