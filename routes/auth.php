<?php

use App\Http\Controllers\ActorsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckUser;
use App\Models\Actor;
use App\Models\Review;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('/',[RegisteredUserController::class, 'create'])->name('register');

    Route::post('register', [RegisteredUserController::class, 'store'])->name('reg');


    Route::get('login', [AuthenticatedSessionController::class, 'create']);

    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');


    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');



});
Route::middleware(CheckUser::class)->group(function(){
    Route::get('userPanel', [UserController::class, 'home'])->name('user.home');
    Route::get('showAllMovie',[UserController::class,'showAllMovie'])->name('user.showMovie');
    Route::post('movieRating', [UserController::class, 'movieRating'])->name('user.movie.review');
    Route::get('showAllSeries',[UserController::class,'showAllSeries'])->name('user.showSeries');
    Route::post('seriesRating', [UserController::class, 'seriesRating'])->name('user.series.review');
    Route::get('seriesReview', [ReviewController::class, 'seriesReview'])->name('user.show.series.review');
    Route::get('movieReview', [ReviewController::class, 'movieReview'])->name('user.show.movie.review');
    Route::get('showActor', [ActorsController::class, 'showActor'])->name('user.show.actors');
    Route::post('user/logout', [AuthenticatedSessionController::class, 'destroy'])->name('user.logout');
    Route::get('Information/{id}/{type}',[UserController::class,'informationAboutMovieOrSeries'])->name('user.Movie_Series');
    Route::get('actorInformation/{id}',[ActorsController::class,'actorInformation'])->name('user.actorInformation');

});




Route::middleware(CheckAdmin::class)->group(function () {
        // >>>>>>>>>>>>>>>>>>Admin<<<<<<<<<<<<<<

Route::get('adminPanel', [AdminController::class, 'home'])->name('admin.home');
Route::post('/admin', [AdminController::class, 'store'])->name('add.admin');
Route::get('/search-admins', [AdminController::class, 'searchAdmins'])->name('search.admins');
Route::post('/admin/update', [AdminController::class, 'update'])->name('update.admin');
Route::delete('admin/{admin}', [AdminController::class, 'destroy'])->name('delete.admin');
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::get('home',[AdminController::class, 'home'])->name('admin.home');
Route::post('/store-admin', [AdminController::class, 'store'])->name('store_admin');
Route::get('admin_page', [AdminController::class, 'admin_page'])->name('admin_page');
Route::post('admin/logout', [AuthenticatedSessionController::class, 'destroy'])->name('admin.logout');





Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');  // لعرض الأفلام
Route::post('/movies', [MovieController::class, 'store'])->name('movies.store');  // لإضافة فيلم جديد
Route::get('/movies/{id}/edit', [MovieController::class, 'edit'])->name('movies.edit');  // لتعديل فيلم
Route::post('/movies/{id}', [MovieController::class, 'update'])->name('movies.update');  // لتحديث فيلم
Route::delete('/movies/{id}', [MovieController::class, 'destroy'])->name('movies.destroy');  // لحذف فيلم


Route::get('/series', [SeriesController::class, 'index'])->name('series.index');  // لعرض المسلسلات
Route::post('/series', [SeriesController::class, 'store'])->name('series.store');  // لإضافة مسلسل جديد
Route::get('/series/{id}/edit', [SeriesController::class, 'edit'])->name('series.edit');  // لتعديل مسلسل
Route::POST('/series/{id}', [SeriesController::class, 'update'])->name('series.update');  // لتحديث مسلسل
Route::delete('/series/{id}', [SeriesController::class, 'destroy'])->name('series.destroy');  // لحذف مسلسل



Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('admin.user.delete');
Route::POST('/user/{id}', [UserController::class, 'update'])->name('admin.user.update');

Route::get('/actors', [ActorsController::class, 'index'])->name('actor.index');
Route::post('/actors', [ActorsController::class, 'store'])->name('actors.store');
Route::get('/actor/{id}/edit', [ActorsController::class, 'edit'])->name('actor.edit');
Route::delete('/actor/{id}', [ActorsController::class, 'destroy'])->name('actor.delete');
Route::POST('/actor/{id}', [ActorsController::class, 'update'])->name('actor.update');


route::get('/reviews',[ReviewController::class,'showAllReviewForAdmin'])->name('admin.show.review');
route::get('/rev',[ReviewController::class,'k'])->name('admin.reviews');
route::get('/delete/review/{id}',[ReviewController::class,'delete'])->name('admin.delete.review');


});


Route::post('/like-review', [ReviewController::class, 'likeReview'])->name('review.like');
Route::get('/actors/{id}', function ($id) {
    $actor = Actor::with(['movies', 'series'])->findOrFail($id);
    return response()->json([
        'name' => $actor->name,
        'movies' => $actor->movies->pluck('name'),
        'series' => $actor->series->pluck('name'),
    ]);
});
Route::get('/actor/{id}', [ActorsController::class, 'getActorDetails']);