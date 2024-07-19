<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::resource('post', PostController::class)
    ->only('store', 'update', 'destroy');
