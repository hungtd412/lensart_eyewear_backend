<?php

use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

//**************************************
// CREATE, DELETE
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-customer'],
], function () {
    Route::get('/wishlists', [WishlistController::class, 'index']);
    Route::post('/wishlists', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy']);
    Route::delete('/wishlists/clear', [WishlistController::class, 'clearWishlist']);
});

//**************************************
// MOVE TO CART
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-customer'],
], function () {
    Route::post('/wishlists/move-to-cart/{wishlistDetailId}', [WishlistController::class, 'moveProductToCart']);
    Route::post('/wishlists/move-all-to-cart', [WishlistController::class, 'moveAllToCart']);
});
