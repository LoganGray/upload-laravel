<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\StorageController;

Route::get('/', [FileUploadController::class, 'index']);
Route::post('/upload', [StorageController::class, 'store']);
Route::delete('/delete/{file}', [StorageController::class, 'destroy']);

