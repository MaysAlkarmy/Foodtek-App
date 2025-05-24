<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('Users', UserController::class);
Route::get('/',[AuthController::class,'index']);
Route::post('/register',[AuthController::class,'register']);
//Route::post('/forgotpassword',[AuthController::class,'forGotPassword']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post("/logout", [AuthController::class, 'logout'])->middleware('auth:api');
});
Route::post('send\otp',[UserController::class,'sendResetLinkEmail']);
Route::post('resetpass',[UserController::class,'resetPassword']);

Route::get('/getItemsByCategory', [ItemController::class, 'getItemsByCategory'])->name('getItemsByCategory');
Route::get('/getMainCategory', [ItemController::class, 'getMainCategory'])->name('getMainCategory');
Route::get('/getItemById/{id}', [ItemController::class, 'getItemById'])->name('getItemById');
Route::post('/createItem', [ItemController::class, 'createItem'])->name('createItem');


Route::post('/addToFavourites', [FavoriteController::class, 'addToFavourites'])->name('addToFavourites');
Route::post('/getUserFavourite', [FavoriteController::class, 'getUserFavourite'])->name('getUserFavourite');
Route::delete('/deleteUserFavourite', [FavoriteController::class, 'deleteUserFavourite'])->name('deleteUserFavourite');

Route::get('/bannerImage', [BannerController::class, 'bannerImage'])->name('bannerImage');

Route::post('/createOffer', [OfferController::class, 'createOffer'])->name('createOffer');
Route::post('/updateOffer{id}', [OfferController::class, 'updateOffer'])->name('updateOffer');
Route::get('/getAllOffer', [OfferController::class, 'getAllOffer'])->name('getAllOffer');

Route::post('sendNotification', action: [NotificationController::class, 'sendNotification']);
Route::post('getUserNotification/{id}', action: [NotificationController::class, 'getUserNotification']);
Route::post('markNotificationAsRead/{id}/{nid}', action: [NotificationController::class, 'markNotificationAsRead']);

Route::post('/createReview', [ReviewController::class, 'createReview'])->name('createReview')->middleware('auth:api');
Route::get('getReview', action: [ReviewController::class, 'getReview']);
Route::post('getItemReviews/{id}', action: [ReviewController::class, 'getItemReviews']);
Route::get('getItemRating/{id}', action: [ReviewController::class, 'getItemRating']);
Route::get('getTopRated', action: [ReviewController::class, 'getTopRated']);

// Route::get('/', function () {
//     return view('test');
// }); 