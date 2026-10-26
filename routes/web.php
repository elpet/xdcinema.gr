<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send')->middleware('throttle:5,10');
