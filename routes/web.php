<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\SessionAuthentication;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return view('welcome');
// });
/* ------- Page routes ------ */
Route::get('/', function () {
    return Inertia::render('HomePage');
});

Route::get('/login', [UserController::class, 'loginPage'])->name('login.page');
Route::get('/registration', [UserController::class, 'registrationPage'])->name('registration.page');

/* ------ All user user routes (auth) ------ */
Route::post('/user-registration', [UserController::class, 'userRegistration'])->name('user.registration');
Route::post('/user-login', [UserController::class, 'userLogin'])->name('user.login');

/* ------ Logged in routes ------ */
Route::middleware(SessionAuthentication::class)->group(function () {
    Route::get('/user-logout', [UserController::class, 'userLogout'])->name('user.logout');
    Route::get('/test', [TestController::class, "testPage"]);

    // Profile page
    Route::get('/profile', [UserController::class, 'UserProfilePage']);
    Route::post('/user-update', [UserController::class, 'userUpdate'])->name('user.update');

    // My Posts
    // Route::get('/my-posts', [PostController::class, 'myPosts']);
    // Route::delete('/posts/{id}', [PostController::class, 'destroy']);
});