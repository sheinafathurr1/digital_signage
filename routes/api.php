<?php

use App\Http\Controllers\Public\DisplayContentsController;
use Illuminate\Support\Facades\Route;

Route::get('/display/{unique_code}/contents', DisplayContentsController::class)
    ->name('api.display.contents');
