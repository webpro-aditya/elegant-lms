<?php

use Illuminate\Support\Facades\Route;
use Modules\Skiply\Http\Controllers\SkiplyController;

Route::get('skiply/success', [SkiplyController::class, 'skiplySuccess'])->name('skiply.success');
Route::get('skiply/test/success', [SkiplyController::class, 'skiplyTestSuccess'])->name('skiply.test.success');
Route::get('skiply/test/failed', [SkiplyController::class, 'skiplyTestFailed'])->name('skiply.test.failed');
