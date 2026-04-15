<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeaturesController;
use App\Http\Controllers\PageController;
use App\Http\Middleware\AccountRecordsMiddleware;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');



Route::get('/about-us', [PageController::class, 'about_us'])->name('about-us');
Route::get('/terms-of-use', [PageController::class, 'terms_of_use'])->name('terms-of-use');
Route::get('/data-privacy', [PageController::class, 'data_privacy'])->name('data-privacy');
Route::get('/articles', [PageController::class, 'articles'])->name('articles');

Route::get('/articles/read/{slug}', [PageController::class, 'articles']);
Route::get('/articles/category/{category}', [PageController::class, 'articles']);
Route::get('/articles/tag/{tag}', [PageController::class, 'articles']);
Route::get('/articles/writer/{owner}', [PageController::class, 'articles']);
Route::get('/articles/date/{date}', [PageController::class, 'articles']);
Route::get('/article/{date}', [PageController::class, 'edit_article']);

Route::get('/articles/read/{slug}', [PageController::class, 'article_slug']);
Route::get('/articles/categories/{category}', [PageController::class, 'article_category']);
Route::get('/articles/tags/{tag}', [PageController::class, 'article_tag']);
Route::get('/articles/writers/{writer}', [PageController::class, 'article_writer']);
Route::get('/articles/dates/{date}', [PageController::class, 'article_date']);

Route::get('/article/', [PageController::class, 'add_article']);


Route::post('/login', [AccountController::class, 'login']);
Route::post('/send-logout-link', [AccountController::class, 'send_logout_link']);
Route::post('/logout', [AccountController::class, 'logout_ajax']);
Route::get('/logout/{token}+{user_id}', [AccountController::class, 'logout_email']);
Route::post('/send-verification-link', [AccountController::class, 'send_verification_link']);
Route::get('/verify/{token}+{user_id}', [AccountController::class, 'verify']);
Route::post('/get-password-reset-otp', [AccountController::class, 'get_password_reset_otp']);
Route::get('/reset-password/{token}+{user_id}', [PageController::class, 'reset_password']);
Route::post('/check-password-reset-otp', [AccountController::class, 'check_password_reset_otp']);
Route::post('/reset-password', [AccountController::class, 'reset_password']);


Route::post('/get-profile', [DashboardController::class, 'get_profile']);
Route::post('/get-authors', [DashboardController::class, 'get_authors']);
Route::post('/get-articles', [DashboardController::class, 'get_articles']);
Route::post('/get-article', [DashboardController::class, 'get_article']);
Route::post('/get-version-body', [DashboardController::class, 'get_version_body']);
Route::post('/get-article-categories', [DashboardController::class, 'get_article_categories']);
Route::post('/get-article-topics', [DashboardController::class, 'get_article_topics']);
Route::post('/add-category', [DashboardController::class, 'add_category']);
Route::post('/add-topic', [DashboardController::class, 'add_topic']);
Route::post('/delete-category', [DashboardController::class, 'delete_category']);
Route::post('/delete-topic', [DashboardController::class, 'delete_topic']);
Route::post('/get-article-versions', [DashboardController::class, 'get_article_versions']);
Route::post('/update-article-status', [DashboardController::class, 'update_article_status']);
Route::post('/save-article', [DashboardController::class, 'save_article']);
Route::post('/delete-article', [DashboardController::class, 'delete_article']);
Route::post('/get-article-image', [DashboardController::class, 'get_article_image']);

Route::post('/get-users', [DashboardController::class, 'get_users']);
Route::post('/get-user', [DashboardController::class, 'get_user']);
Route::post('/save-user', [DashboardController::class, 'save_user']);
Route::post('/delete', [DashboardController::class, 'delete']);


Route::post('/get-searched-articles', [FeaturesController::class, 'get_searched_articles']);
Route::post('/get-featured-articles', [FeaturesController::class, 'get_featured_articles']);
Route::post('/get-featured-categories', [FeaturesController::class, 'get_featured_categories']);