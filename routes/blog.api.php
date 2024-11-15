<?php

use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

//**************************************
//  SHOW PRODUCT ON HOMEPAGE
//**************************************
Route::get('/blogs', [BlogController::class, 'getBlogs']);
