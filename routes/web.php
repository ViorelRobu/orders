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

Route::middleware(['auth'])->group(function() {
    Route::get('/', function() {
        return view('home');
    });
    /*
     |---------------------------------------------------------------
     |  Nomenclator routes
     |---------------------------------------------------------------
     */
    Route::prefix('/countries')->group(function() {
        Route::get('/', 'CountriesController@index');
        Route::get('/all', 'CountriesController@fetchAll')->name('countries.index');
        Route::get('/fetch', 'CountriesController@fetch')->name('countries.fetch');
        Route::post('/add','CountriesController@store');
        Route::patch('/{country}/update', 'CountriesController@update');
    });

    Route::prefix('/customers')->group(function() {
        Route::get('/', 'CustomersController@index');
        Route::get('/all', 'CustomersController@fetchAll')->name('customers.index');
        Route::get('/fetch', 'CustomersController@fetch')->name('customers.fetch');
        Route::post('/add', 'CustomersController@store');
        Route::prefix('/{customer}')->group(function() {
            Route::patch('/update', 'CustomersController@update');
            Route::get('/destinations', 'DestinationController@fetch')->name('destination.fetch');
            Route::post('/destinations/find', 'DestinationController@findOrNew')->name('destination.findOrNew');
            Route::post('/destinations/search', 'DestinationController@search');
        });
    });

    Route::prefix('/species')->group(function() {
        Route::get('/', 'SpeciesController@index');
        Route::get('/all', 'SpeciesController@fetchAll')->name('species.index');
        Route::get('/fetch', 'SpeciesController@fetch')->name('species.fetch');
        Route::post('/add', 'SpeciesController@store');
        Route::patch('/{species}/update', 'SpeciesController@update');
    });

    Route::prefix('/products')->group(function() {
        Route::get('/', 'ProductTypesController@index');
        Route::get('/all', 'ProductTypesController@fetchAll')->name('products.index');
        Route::get('/fetch', 'ProductTypesController@fetch')->name('products.fetch');
        Route::post('/add', 'ProductTypesController@store');
        Route::patch('/{product}/update', 'ProductTypesController@update');
    });

    Route::prefix('/quality')->group(function() {
        Route::get('/', 'QualityController@index');
        Route::get('/all', 'QualityController@fetchAll')->name('quality.index');
        Route::get('/fetch', 'QualityController@fetch')->name('quality.fetch');
        Route::post('/add', 'QualityController@store');
        Route::patch('/{quality}/update', 'QualityController@update');
    });

    Route::prefix('/refinements')->group(function() {
        Route::get('/', 'RefinementsController@index');
        Route::get('/all', 'RefinementsController@fetchAll')->name('refinements.index');
        Route::get('/fetch', 'RefinementsController@fetch')->name('refinements.fetch');
        Route::post('/add', 'RefinementsController@store');
        Route::patch('/{refinement}/update', 'RefinementsController@update');
    });

    Route::prefix('/articles')->group(function() {
        Route::get('/', 'ArticlesController@index');
        Route::get('/all', 'ArticlesController@fetchAll')->name('articles.index');
        Route::get('/fetch', 'ArticlesController@fetch')->name('articles.fetch');
        Route::post('/add', 'ArticlesController@store');
        Route::patch('/{article}/update', 'ArticlesController@update');
    });

    Route::prefix('/numbers')->group(function() {
        Route::get('/', 'OrderNumbersController@index');
        Route::get('/all', 'OrderNumbersController@fetchAll')->name('order_numbers.index');
        Route::post('/add', 'OrderNumbersController@store');
    });

    Route::prefix('/articles')->group(function () {
        Route::get('/', 'ArticlesController@index');
        Route::get('/all', 'ArticlesController@fetchAll')->name('articles.index');
        Route::get('/fetch', 'ArticlesController@fetch')->name('articles.fetch');
        Route::post('/add', 'ArticlesController@store');
        Route::patch('/{article}/update', 'ArticlesController@update');
    });

    /*
     |---------------------------------------------------------------
     |  Orders routes
     |---------------------------------------------------------------
     */
    Route::prefix('/orders')->group(function () {
        Route::get('/', 'OrdersController@index');
        Route::get('/all', 'OrdersController@fetchAll')->name('orders.index');
        Route::get('/fetch', 'OrdersController@fetch')->name('orders.fetch');
        Route::post('/add', 'OrdersController@store');
        Route::prefix('/{order}/')->group(function() {
            Route::patch('/update', 'OrdersController@update');
            Route::patch('/update/priority', 'OrdersController@setPriority');
            Route::patch('/update/details', 'OrdersController@setDetails');
            Route::get('/show', 'OrdersController@show');
        });
    });

});
