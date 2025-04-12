<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\SessionAuthentication;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return view('welcome');
// });

/* ------ All user user routes (auth) ------ */
Route::post('/user-registration', [UserController::class, 'userRegistration'])->name('user.registration');
Route::post('/user-login', [UserController::class, 'userLogin'])->name('user.login');

/* ------ Logged in routes ------ */
Route::middleware(SessionAuthentication::class)->group(function () {
    Route::get('/user-logout', [UserController::class, 'userLogout'])->name('user.logout');
    Route::get('/test', [TestController::class, "testPage"]);

    // Profile page and update route
    Route::get('/profile', [UserController::class, 'UserProfilePage']);
    Route::post('/user-update', [UserController::class, 'userUpdate'])->name('user.update');

    // Post
    // Route::delete('/posts/{id}', [PostController::class, 'destroy']);
    Route::get('/my-posts', [PostController::class, 'myPosts']);
    Route::get('/createPostPage', [PostController::class, 'createPostPage'])->name('posts.create');
    Route::get('/updatePostPage/{id}', [PostController::class, 'updatePostPage'])->name('posts.update.page');
    Route::post('/post-create', [PostController::class, 'createPost'])->name('post.create');
    Route::post('/post-update/{id}', [PostController::class, 'updatePost'])->name('post.update');

    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
});
/* ------- Page routes ------ */
// Route::get('/', function () {
//     return Inertia::render('HomePage');
// });

Route::get('/login', [UserController::class, 'loginPage'])->name('login.page');
Route::get('/registration', [UserController::class, 'registrationPage'])->name('registration.page');

/* --- Post CRUD routes ---*/
Route::get('/', [PostController::class, 'allPost'])->name('posts.all');


/* 

// Show all posts (public posts for guests)
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

// Show form to create a new post
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');

// Store a new post
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

// Show form to edit an existing post
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');

// Update an existing post
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');

// Delete a post
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
*/