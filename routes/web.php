<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\IndexController;

Route::get('/', [IndexController::class, 'index'])->name('inicio');
Route::post('/insert', [IndexController::class, 'insert'])->name('insert');
Route::post('/upload', [UploadController::class, 'upload'])->name('upload');
Route::post('/update', [IndexController::class, 'update'])->name('update');
Route::post('/delete', [IndexController::class, 'delete'])->name('delete');