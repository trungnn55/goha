<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api', 'middleware' => ['authorize',]], function () {
    Route::post('/auth/sign-in', 'AuthController@signIn');
    Route::post('/auth/sign-up', 'AuthController@signUp');
    Route::get('/user/profile', 'UserController@getProfile');
    Route::post('/user/update-profile', 'UserController@updateProfile');
    Route::resource('/banner', 'BannerController');
    Route::get('/product-sale', 'ProductController@productSale');
    Route::get('/hot-deal', 'ProductController@hotDeal');
    Route::get('/favorite', 'ProductController@favorite');
    Route::post('/add-favorite', 'UserController@addFavorite');
});