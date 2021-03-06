<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PageController@index');
Route::get('/about', 'PageController@about')->name('about');
Route::get('/contact', 'PageController@contact')->name('contact');
Route::post('/payment', 'PaymentController@checkout')->name('payment');
Route::get('/payment/verify', 'PaymentController@verify')->name('verify');


Auth::routes(['verify' => true]);
Route::post('/register', 'Auth\RegisterController@setData');
Route::get('/register/p2', 'Auth\RegisterController@showSecondRegisterForm');


Route::get('/password/change', 'Auth\ChangePasswordController@showChangeForm')->name('password.change');
Route::patch('/password/update', 'Auth\ChangePasswordController@update')->name('password.auth.update');


Route::get('/login/reviewer', 'Auth\LoginController@showReviewerLoginForm');
Route::post('/login/reviewer', 'Auth\LoginController@reviewerLogin')->name('login.reviewer');
Route::get('/register/reviewer', 'Auth\RegisterController@showReviewerRegisterForm');
Route::get('/register/reviewer/p2', 'Auth\RegisterController@showSecondRegisterForm');
Route::post('/register/reviewer', 'Auth\RegisterController@createReviewer')->name('register.reviewer');

Route::prefix('reviewer')->group(function () {
    Route::get('/profile', 'ReviewerController@profile')->name('reviewer.profile');
    Route::post('/profile/available', 'ReviewerController@makeAvailable')->name('reviewer.available');
    Route::post('/profile/not-available', 'ReviewerController@makeNotAvailable')->name('reviewer.not.available');

});
Route::resource('reviewers', 'ReviewerController')->only(['index', 'show', 'update']);

Route::post('/{reviewer}/{comment}/score', 'ReviewerCommentScoreController')->name('reviewer.score')->middleware('auth:web');


Route::resource('users', 'UserController')->only(['show', 'update']);
Route::get('/user/profile', 'UserController@profile')->name('user.profile')->middleware('verified');
Route::prefix('users')->group(function () {
    Route::get('/{user}/allow_reviewer/{reviewer}', 'UserController@allow_reviewer')->name('allow_reviewer');
    Route::get('/{user}/forbid_reviewer/{reviewer}', 'UserController@forbid_reviewer')->name('forbid_reviewer');
    Route::get('/{user}/download_cv/{reviewer}', 'UserController@download_cv')->name('cv_download');
    Route::post('/{user}/comment', 'CommentController@store')->name('comment.store');

});

Route::get('/requests', 'RequestController@index')->name('requests.index');
Route::resource('comments', 'CommentController')->only(['index', 'show']);


Route::get('login/reviewer/{provider}', 'Auth\SocialiteController@redirectToProvider')->name('reviewer.social');
Route::get('login/{provider}', 'Auth\SocialiteController@redirectToProvider')->name('user.social');
Route::get('callback/{provider}', 'Auth\SocialiteController@handleProviderCallback');