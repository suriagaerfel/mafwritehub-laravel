<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PageController;
use App\Http\Middleware\AccountRecordsMiddleware;






Route::get('/', [PageController::class, 'home']);
Route::get('/dashboard', [PageController::class, 'dashboard']);
Route::get('/articles', [PageController::class, 'articles']);


Route::get('/about-us', [PageController::class, 'about_us']);
Route::get('/terms-of-use', [PageController::class, 'terms_of_use']);
Route::get('/data-privacy', [PageController::class, 'data_privacy']);

Route::get('/articles/read/{slug}', [PageController::class, 'articles']);
Route::get('/articles/category/{category}', [PageController::class, 'articles']);
Route::get('/articles/tag/{tag}', [PageController::class, 'articles']);
Route::get('/articles/writer/{owner}', [PageController::class, 'articles']);
Route::get('/articles/date/{date}', [PageController::class, 'articles']);








// Route::post('/send-verification-link', [AccountController::class, 'send_verification_link']);
// Route::post('/verify/{verification-code}', [AccountController::class, 'send_verification_link']);
// Route::get('/verify/{registrantCode}/{verificationCode}', [AccountController::class, 'verify']);
// Route::post('/logout', [AccountController::class, 'logout_ajax']);
// Route::get('/logout/{user_code}/{token}', [AccountController::class, 'logout_email']);

// Route::post('/send-logout-link', [AccountController::class, 'send_logout_link']);

Route::post('/login', [AccountController::class, 'login']);

