<?php

use Illuminate\Support\Facades\Route;
use Modules\QuestionPool\Http\Controllers\QuestionPoolController;
use Modules\QuestionPool\Http\Controllers\PracticeQuizController;

Route::middleware(['auth', 'admin'])->prefix('question-pool')->group(function () {
    Route::get('/', [QuestionPoolController::class, 'index'])->name('question-pool.index');
    Route::get('/create', [QuestionPoolController::class, 'create'])->name('question-pool.create');
    Route::post('/', [QuestionPoolController::class, 'store'])->name('question-pool.store');
    // Bulk Import (must be before /{id} routes)
    Route::get('/bulk-import', [QuestionPoolController::class, 'bulkImport'])->name('question-pool.bulk-import');
    Route::post('/bulk-import', [QuestionPoolController::class, 'bulkImportSubmit'])->name('question-pool.bulk-import-submit');
    Route::get('/download-sample', [QuestionPoolController::class, 'downloadSample'])->name('question-pool.download-sample');

    Route::get('/{id}/edit', [QuestionPoolController::class, 'edit'])->name('question-pool.edit');
    Route::post('/{id}', [QuestionPoolController::class, 'update'])->name('question-pool.update');
    Route::post('/delete', [QuestionPoolController::class, 'destroy'])->name('question-pool.delete');
    
    // Ajax dropdowns
    Route::get('/get-chapters/{courseId}', [QuestionPoolController::class, 'getChapters'])->name('question-pool.get-chapters');
    Route::get('/get-lessons/{chapterId}', [QuestionPoolController::class, 'getLessons'])->name('question-pool.get-lessons');
});

// Student Practice Quiz routes
Route::middleware(['auth'])->group(function () {
    Route::get('/practice-quiz/start-lesson/{lessonId}', [PracticeQuizController::class, 'startLesson'])->name('practice-quiz.start-lesson');
    Route::get('/practice-quiz/setup/{courseId}', [PracticeQuizController::class, 'setup'])->name('practice-quiz.setup');
    Route::get('/practice-quiz/available-count', [PracticeQuizController::class, 'availableCount'])->name('practice-quiz.available-count');
    Route::post('/practice-quiz/start', [PracticeQuizController::class, 'start'])->name('practice-quiz.start');
    Route::get('/practice-quiz/attempt/{id}', [PracticeQuizController::class, 'attempt'])->name('practice-quiz.attempt');
    Route::post('/practice-quiz/submit', [PracticeQuizController::class, 'submit'])->name('practice-quiz.submit');
    Route::get('/practice-quiz/result/{id}', [PracticeQuizController::class, 'result'])->name('practice-quiz.result');
    Route::get('/my-practice-quizzes', [PracticeQuizController::class, 'history'])->name('practice-quiz.history');
});
