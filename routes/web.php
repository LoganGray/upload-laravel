<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;



Route::get('/', [FileUploadController::class, 'index']);
Route::post('/upload', [FileUploadController::class, 'store']);
Route::delete('/delete/{file}', [FileUploadController::class, 'destroy']);
