<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => '/'], function ($router) {
    Route::post('auth/login', 'HomeController@login');
    Route::post('auth/logout', 'HomeController@logout');
    Route::post('/post/create', 'HomeController@store');
    Route::get('/home', 'HomeController@index');
    Route::get('/confirmation', 'HomeController@resultConfirmation');
    Route::get('/block', 'HomeController@resultBlockaccount');
    Route::get('/unblock', 'HomeController@resultUnblockaccount');
    Route::delete('/post/delete/{id}', 'HomeController@delete');
    Route::post('/post/confirmation/{id}', 'HomeController@confirmation');
    Route::post('/post/block/{id}', 'HomeController@block');
    Route::post('/post/unblock/{id}', 'HomeController@unblock');
    // getinfo Shop-Customer
    Route::post('/post/getinfoShop/{id}', 'HomeController@getinfoShop');
    Route::post('/post/getinfoCustomer/{id}', 'HomeController@getinfoCustomer');
    Route::post('/post/addgroup', 'HomeController@addgroup');
    Route::get('/post/order', 'HomeController@order');
    Route::post('/post/cofirm/{id}', 'HomeController@cofirm');
    Route::post('/post/cancel/{id}', 'HomeController@cancel');
    Route::post('/post/detail/{id}', 'HomeController@detail');
    Route::get('/post/approved', 'HomeController@approved');
    Route::get('/post/canceled', 'HomeController@canceled');
    Route::get('/product', 'HomeController@product');
    Route::get('/comment', 'HomeController@comment');
    Route::delete('/comment/deletecmt/{id}', 'HomeController@deletecmt');
    Route::post('/post/detailproduct/{id}', 'HomeController@detailproduct');
    Route::post('/chartLine/groupbar/{id}', 'HomeController@groupbar');
    Route::post('/chartLine/totalbar/{id}', 'HomeController@totalbar');
    Route::post('/chartLine/totalline/{id}', 'HomeController@totalline');
});

Route::group(['prefix' => '/shop'], function ($router) {
    Route::post('/{id}', 'ShopController@index');
    Route::post('/post/create/{id}', 'ShopController@store');
    Route::get('/getgroup', 'ShopController@getgroup');
    Route::post('/post/home/{id}', 'ShopController@home');
    Route::post('/post/delete/{id}', 'ShopController@delete');
    Route::post('/post/getinfupdate/{id}', 'ShopController@getinfupdate');
    Route::post('/post/update/{id}', 'ShopController@update');
    Route::post('/post/getfee/{id}', 'ShopController@getfee');
    Route::post('/post/addfee/{id}', 'ShopController@addfee');
    Route::post('/post/getinfoShop/{id}', 'ShopController@getinfoShop');
    Route::post('/post/updateinfShop/{id}', 'ShopController@updateinfShop');
    Route::post('/post/updatecreditShop/{id}', 'ShopController@updatecreditShop');
    Route::post('/post/updateAvatar/{id}', 'ShopController@updateAvatar');
    Route::post('/post/order/{id}', 'ShopController@order');
    Route::post('/post/cofirm/{id}', 'ShopController@listcofirm');
    Route::post('/post/confirm/{id}', 'ShopController@confirm');
    Route::post('/post/cancel/{id}', 'ShopController@cancel');
    Route::post('/post/listcancel/{id}', 'ShopController@listcancel');
    Route::post('/chartLine/groupbar/{year}', 'ShopController@groupbar');
    Route::post('/chartLine/totalbar/{id}', 'ShopController@totalbar');
    Route::post('/chartLine/totalline/{id}', 'ShopController@totalline');
    Route::post('/post/detailproduct/{id}', 'ShopController@detailproduct');
});


Route::group(['prefix' => '/phone'], function ($router) {
    Route::post('/login','PhoneHomeController@login');
    Route::get('/product', 'PhoneHomeController@product');
    Route::post('/favorite','PhoneHomeController@favorite');
    Route::post('/checkFavorite', 'PhoneHomeController@checkFavorite');
    Route::post('/checkdiscount', 'PhoneHomeController@checkdiscount');
    Route::post('/phone','PhoneHomeController@phone');
    Route::post('/register','PhoneHomeController@register');
    Route::post('/shop', 'PhoneHomeController@shop');
    Route::post('/ProductShopSale','PhoneHomeController@ProductShopSale');
    Route::post('/ProductShop','PhoneHomeController@ProductShop');
    Route::post('/follow','PhoneHomeController@follow');
    Route::post('/checkFollow','PhoneHomeController@checkFollow');
    Route::post('/countFollow','PhoneHomeController@countFollow');
    Route::post('/order','PhoneHomeController@order');
    Route::post('/ordercard','PhoneHomeController@ordercard');
    Route::get('/highestdiscount','PhoneHomeController@highestdiscount');
    Route::get('/limited','PhoneHomeController@limited');
    Route::get('/hasdiscount','PhoneHomeController@hasdiscount');
    Route::post('/getprofile','PhoneHomeController@getprofile');
    Route::post('/saveprofile','PhoneHomeController@saveprofile');
    Route::post('/shopfollow','PhoneHomeController@shopfollow');
    Route::post('/productfavorite','PhoneHomeController@productfavorite');
    Route::post('/commentshop','PhoneHomeController@commentshop');
    Route::post('/ratingproduct','PhoneHomeController@ratingproduct');
    Route::post('/listcomment','PhoneHomeController@listcomment');
    // ------------------Ace.Lyon.Thon-----------------
    Route::post('/retrieve','PhoneHomeController@retrieve');
    Route::post('/updateimage','PhoneHomeController@updateImage');
    Route::post('/updatebackground','PhoneHomeController@updateBackground');
    Route::post('/getservice','PhoneHomeController@getService');
    Route::post('/getpost','PhoneHomeController@getListPost');
    Route::post('/checklike', 'PhoneHomeController@checklike');
    Route::post('/like', 'PhoneHomeController@like');
});

