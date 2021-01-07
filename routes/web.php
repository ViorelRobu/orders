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
        Route::get('/audits', 'CountriesController@audits');
        Route::post('/add','CountriesController@store');
        Route::patch('/{country}/update', 'CountriesController@update');
    });

    Route::prefix('/customers')->group(function() {
        Route::get('/', 'CustomersController@index');
        Route::get('/all', 'CustomersController@fetchAll')->name('customers.index');
        Route::get('/fetch', 'CustomersController@fetch')->name('customers.fetch');
        Route::get('/audits', 'CustomersController@audits');
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
        Route::get('/audits', 'SpeciesController@audits');
        Route::post('/add', 'SpeciesController@store');
        Route::patch('/{species}/update', 'SpeciesController@update');
    });

    Route::prefix('/products')->group(function() {
        Route::get('/', 'ProductTypesController@index');
        Route::get('/all', 'ProductTypesController@fetchAll')->name('products.index');
        Route::get('/fetch', 'ProductTypesController@fetch')->name('products.fetch');
        Route::get('/audits', 'ProductTypesController@audits');
        Route::post('/add', 'ProductTypesController@store');
        Route::patch('/{product}/update', 'ProductTypesController@update');
    });

    Route::prefix('/quality')->group(function() {
        Route::get('/', 'QualityController@index');
        Route::get('/all', 'QualityController@fetchAll')->name('quality.index');
        Route::get('/fetch', 'QualityController@fetch')->name('quality.fetch');
        Route::get('/audits', 'QualityController@audits');
        Route::post('/add', 'QualityController@store');
        Route::patch('/{quality}/update', 'QualityController@update');
    });

    Route::prefix('/refinements')->group(function() {
        Route::get('/', 'RefinementsController@index');
        Route::get('/all', 'RefinementsController@fetchAll')->name('refinements.index');
        Route::get('/fetch', 'RefinementsController@fetch')->name('refinements.fetch');
        Route::get('/audits', 'RefinementsController@audits');
        Route::post('/add', 'RefinementsController@store');
        Route::patch('/{refinement}/update', 'RefinementsController@update');
    });

    Route::prefix('/articles')->group(function() {
        Route::get('/', 'ArticlesController@index');
        Route::get('/all', 'ArticlesController@fetchAll')->name('articles.index');
        Route::get('/fetch', 'ArticlesController@fetch')->name('articles.fetch');
        Route::get('/audits', 'ArticlesController@audits');
        Route::post('/add', 'ArticlesController@store');
        Route::post('/import', 'ArticlesController@import');
        Route::patch('/{article}/update', 'ArticlesController@update');
    });

    Route::prefix('/numbers')->group(function() {
        Route::get('/', 'OrderNumbersController@index');
        Route::get('/all', 'OrderNumbersController@fetchAll')->name('order_numbers.index');
        Route::get('/audits', 'OrderNumbersController@audits');
        Route::post('/add', 'OrderNumbersController@store');
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
        Route::get('/audits', 'OrdersController@audits');
        Route::get('/details/audits', 'OrderDetailsController@audits')->name('details.audits');
        Route::post('/print/multiple', 'OrdersController@printMultiple');
        Route::post('/add', 'OrdersController@store');
        Route::prefix('/{order}/')->group(function() {
            Route::get('/show', 'OrdersController@show');
            Route::post('/copy', 'OrdersController@copy');
            Route::get('/print/{orientation}', 'OrdersController@print');
            Route::prefix('/documents')->group(function() {
                Route::get('/fetch', 'OrdersController@fetchAttachments');
                Route::post('/upload', 'OrdersController@uploadAttachment');
                Route::delete('/{document}/delete', 'OrdersController@deleteAttachment');
            });
            Route::prefix('/details')->group(function() {
                Route::get('/', 'OrderDetailsController@fetch');
                Route::post('/add', 'OrderDetailsController@store');
                Route::post('/copy', 'OrderDetailsController@copy');
                Route::delete('/package/delete', 'OrderDetailsController@destroyPackage');
                Route::prefix('/{position}')->group(function() {
                    Route::get('/fetch', 'OrderDetailsController@getPosition');
                    Route::patch('/update', 'OrderDetailsController@update');
                    Route::delete('/delete', 'OrderDetailsController@destroyPosition');
                });
            });
            Route::post('/fields', 'OrdersController@fields');
            Route::prefix('/update')->group(function() {
                Route::patch('/', 'OrdersController@update');
                Route::patch('/priority', 'OrdersController@setPriority');
                Route::patch('/details', 'OrdersController@setDetails');
                Route::patch('/observations', 'OrdersController@setObservations');
                Route::patch('/dates', 'OrdersController@setDates');
            });
            Route::patch('/ship', 'OrdersController@ship');
        });
    });

    /*
     |---------------------------------------------------------------
     |  Orders archive routes
     |---------------------------------------------------------------
     */
    Route::prefix('/archive')->group(function() {
        Route::get('/', 'OrdersController@archive');
        Route::get('/all', 'OrdersController@fetchAllArchive')->name('archive.index');
    });


    /*
     |---------------------------------------------------------------
     |  Reports routes
     |---------------------------------------------------------------
     */
     Route::prefix('/reports')->group(function() {
        Route::get('/', 'ReportsController@index');
        Route::get('/archive', 'ReportsController@indexArchive');
        Route::get('archive/fetch', 'ReportsController@fetchArchive')->name('archive.fetch');
        Route::get('/active', 'ReportsController@exportActiveOrders');
        Route::get('/production/plan', 'ReportsController@exportProductionPlan');
     });


    /*
     |---------------------------------------------------------------
     |  Import routes
     |---------------------------------------------------------------
     */
    Route::prefix('/import')->group(function () {
        Route::get('/', 'ReportsController@imports');
        Route::get('/archive', 'ReportsController@indexImports');
        Route::get('archive/fetch', 'ReportsController@fetchImports')->name('imports.fetch');
        Route::post('production/start', 'ReportsController@importProduction');
        Route::post('production/plan/start', 'ReportsController@importProductionPlan');
        Route::post('deliveries/start', 'ReportsController@importDeliveries');
    });

    /*
     |---------------------------------------------------------------
     |  User management routes
     |---------------------------------------------------------------
     */
    Route::prefix('/users')->group(function () {
        Route::get('/', 'UsersController@index');
        Route::post('/add', 'UsersController@store');
        Route::get('/all', 'UsersController@fetchAll')->name('users.index');
        Route::get('/fetch', 'UsersController@fetch');
        Route::patch('/{id}/update', 'UsersController@update');
    });

});
