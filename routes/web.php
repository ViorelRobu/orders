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

Auth::routes();

Route::get('/home', function() {
    return view('home');
})->name('home')->middleware('auth');
Route::get('/orders', 'OrdersController@index')->middleware('auth');
Route::get('/orders/fetch', 'OrdersController@fetch')->name('orders.index')->middleware('auth');
Route::post('/orders/add', 'OrdersController@create')->middleware('auth');
Route::get('/orders/{order}', 'OrdersController@show')->name('details.index')->middleware('auth');
Route::post('/orders/details/add', 'OrdersController@addDetails')->middleware('auth');
