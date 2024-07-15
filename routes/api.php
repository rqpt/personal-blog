<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::controller(PostController::class)->group(function () {
    Route::post('post', 'store');
    Route::put('post/{post:title}', 'update');
    Route::delete('post/{post:title}', 'destroy');
});
