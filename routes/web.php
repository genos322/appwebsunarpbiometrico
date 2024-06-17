<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\IndexController;

Route::get('/', [IndexController::class, 'index'])->name('inicio');
Route::post('/upload', [UploadController::class, 'upload'])->name('upload');