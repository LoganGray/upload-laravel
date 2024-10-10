<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');


    
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;

Route::get('/', [FileUploadController::class, 'index']);
Route::post('/upload', [FileUploadController::class, 'store']);
