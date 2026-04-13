<?php

use App\Http\Controllers\VideoUploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [VideoUploadController::class, 'index']);
Route::match(['get', 'post'], '/upload-chunk', [VideoUploadController::class, 'upload']);

