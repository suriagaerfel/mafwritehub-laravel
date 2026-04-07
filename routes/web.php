<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PageController;
use App\Http\Middleware\AccountRecordsMiddleware;






Route::get('/', [PageController::class, 'home']);
Route::get('/articles', [PageController::class, 'articles']);
Route::get('/teacher-files', [PageController::class, 'teacher_files']);
Route::get('/researches', [PageController::class, 'researches']);
Route::get('/tools', [PageController::class, 'tools']);
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::get('/create-account', [PageController::class, 'create_account'])->name('create-account');
Route::get('/account', [PageController::class, 'account']);
Route::get('/get-password-reset-link', [PageController::class, 'get_password_reset_link'])->name('get-password-reset-link');
Route::get('/reset-password/{userCode}/{token}', [PageController::class, 'reset_password']);
Route::get('/search', [PageController::class, 'search']);
Route::get('/messages', [PageController::class, 'messages']);
Route::get('/about-us', [PageController::class, 'about_us']);
Route::get('/terms-of-use', [PageController::class, 'terms_of_use']);
Route::get('/data-privacy', [PageController::class, 'data_privacy']);
Route::get('/workspace/writer', [PageController::class, 'workspace_writer']);
Route::get('/workspace/editor', [PageController::class, 'workspace_editor']);
Route::get('/workspace/teacher', [PageController::class, 'workspace_teacher']);
Route::get('/workspace/developer', [PageController::class, 'workspace_developer']);
Route::get('/workspace/researches', [PageController::class, 'workspace_researches']);
Route::get('/workspace/website-manager', [PageController::class, 'workspace_website_manager']);

Route::get('/articles/read/{slug}', [PageController::class, 'articles']);
Route::get('/articles/category/{category}', [PageController::class, 'articles']);
Route::get('/articles/tag/{tag}', [PageController::class, 'articles']);
Route::get('/articles/writer/{owner}', [PageController::class, 'articles']);
Route::get('/articles/date/{date}', [PageController::class, 'articles']);


Route::get('/teacher-files/view/{slug}', [PageController::class, 'teacher_files']);
Route::get('/teacher-files/category/{category}', [PageController::class, 'teacher_files']);
Route::get('/teacher-files/tag/{tag}', [PageController::class, 'teacher_files']);
Route::get('/teacher-files/teacher/{owner}',[PageController::class, 'teacher_files']);
Route::get('/teacher-files/date/{date}', [PageController::class, 'teacher_files']);






Route::get('/researches/view/{slug}', [PageController::class, 'researches']);
Route::get('/researches/category/{category}',[PageController::class, 'researches']);
Route::get('/researches/tag/{tag}', [PageController::class, 'researches']);
Route::get('/researches/school/{owner}', [PageController::class, 'researches']);
Route::get('/researches/date/{date}',[PageController::class, 'researches']);





Route::get('/tools/category/{category}', [PageController::class, 'tools']);
Route::get('/tools/tag/{tag}', [PageController::class, 'tools']);
Route::get('/tools/developer/{owner}', [PageController::class, 'tools']);
Route::get('/tools/date/{date}', [PageController::class, 'tools']);







Route::get('/{user}', [PageController::class, 'user']);
Route::post('/get-password-reset-link', [AccountController::class, 'get_password_reset_link']);Route::post('/reset-password', [AccountController::class, 'reset_password']);
Route::post('/get-profile', [AccountController::class, 'get_profile']);
Route::post('/login', [AccountController::class, 'login']);
Route::post('/create-account', [AccountController::class, 'create_account']);
Route::post('/send-verification-link', [AccountController::class, 'send_verification_link']);
Route::post('/verify/{verification-code}', [AccountController::class, 'send_verification_link']);
Route::get('/verify/{registrantCode}/{verificationCode}', [AccountController::class, 'verify']);
Route::post('/logout', [AccountController::class, 'logout_ajax']);
Route::get('/logout/{user_code}/{token}', [AccountController::class, 'logout_email']);
Route::post('/send-logout-link', [AccountController::class, 'send_logout_link']);
Route::post('/update-profile-details', [AccountController::class, 'update_profile_details']);
Route::post('/upload-profile-picture', [AccountController::class, 'upload_for_profile']);
Route::post('/upload-cover-photo', [AccountController::class, 'upload_for_profile']);
Route::post('/check-other-registration', [AccountController::class, 'check_other_registration']);
Route::post('/other-registration-submit', [AccountController::class, 'other_registration_submit']);
