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
Route::get('/test', function () {
    return view('test');
});

Route::apiResource('Users', UserController::class);
Route::get('/',[UserController::class,'index']);
Route::post('/register',[UserController::class,'register']);
//Route::post('/forgotpassword',[UserController::class,'forGotPassword']);
Route::post('/login',[UserController::class,'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post("/logout", [UserController::class, 'logout'])->middleware('auth:api');
});
Route::post('send\otp',[UserController::class,'sendResetLinkEmail']);
Route::post('resetpass',[UserController::class,'resetPassword']);

Route::get('/getItemsByCategory', [ItemController::class, 'getItemsByCategory'])->name('getItemsByCategory');
Route::get('/getMainCategory', [ItemController::class, 'getMainCategory'])->name('getMainCategory');
Route::get('/getItemById/{itemId}', [ItemController::class, 'getItemById'])->name('getItemById');
Route::post('/createItem', [ItemController::class, 'createItem'])->name('createItem');

Route::post('/addToCart', [ItemController::class, 'addToCart'])->name('addToCart')->middleware('auth:api');
Route::get('/viewUserCart', [ItemController::class, 'viewUserCart'])->name('viewUserCart')->middleware('auth:api');
Route::delete('/removeItemFromCart', [ItemController::class, 'removeItemFromCart'])->name('removeItemFromCart')->middleware('auth:api');

Route::post('/addToFavourites', [FavoriteController::class, 'addToFavourites'])->name('addToFavourites')->middleware('auth:api');
Route::get('/getUserFavourite', [FavoriteController::class, 'getUserFavourite'])->name('getUserFavourite')->middleware('auth:api');
Route::delete('/deleteUserFavourite', [FavoriteController::class, 'deleteUserFavourite'])->name('deleteUserFavourite')->middleware('auth:api');

Route::get('/bannerImage', [BannerController::class, 'bannerImage'])->name('bannerImage');

Route::post('/createOffer', [OfferController::class, 'createOffer'])->name('createOffer');
Route::post('/updateOffer{id}', [OfferController::class, 'updateOffer'])->name('updateOffer');
Route::get('/getAllOffer', [OfferController::class, 'getAllOffer'])->name('getAllOffer');

Route::post('sendNotification', action: [NotificationController::class, 'sendNotification']);
Route::get('getUserNotification/{id}', action: [NotificationController::class, 'getUserNotification']);
Route::post('markNotificationAsRead/{id}/{nid}', action: [NotificationController::class, 'markNotificationAsRead']);

Route::post('/createReview', [ReviewController::class, 'createReview'])->name('createReview')->middleware('auth:api');
Route::get('getReview', action: [ReviewController::class, 'getReview']);
Route::post('getItemReviews/{itemId}', action: [ReviewController::class, 'getItemReviews']);
Route::get('getItemRating/{id}', action: [ReviewController::class, 'getItemRating']);
Route::get('getTopRated', action: [ReviewController::class, 'getTopRated']);

// Route::get('/', function () {
//     return view('test');
// }); 