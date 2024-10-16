<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\StorageController;

Route::get('/', [FileUploadController::class, 'index']);
Route::post('/upload', [StorageController::class, 'store'])->name('upload');
Route::delete('/delete/{file}', [StorageController::class, 'destroy'])->name('delete');
Route::get('/categorize', [StorageController::class, 'categorize'])->name('categorize');
Route::post('/categorize/{file}', [StorageController::class, 'updateCategory'])->name('updateCategory');

