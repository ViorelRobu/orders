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
        Route::get('/', 'CountriesController@index')->middleware('can:planificare');
        Route::get('/all', 'CountriesController@fetchAll')->name('countries.index')->middleware('can:planificare');
        Route::get('/fetch', 'CountriesController@fetch')->name('countries.fetch')->middleware('can:planificare');
        Route::get('/audits', 'CountriesController@audits')->middleware('can:planificare');
        Route::post('/add','CountriesController@store')->middleware('can:planificare');
        Route::patch('/{country}/update', 'CountriesController@update')->middleware('can:planificare');
    });

    Route::prefix('/customers')->group(function() {
        Route::get('/', 'CustomersController@index')->middleware('can:planificare');
        Route::get('/all', 'CustomersController@fetchAll')->name('customers.index')->middleware('can:planificare');
        Route::get('/fetch', 'CustomersController@fetch')->name('customers.fetch')->middleware('can:planificare');
        Route::get('/audits', 'CustomersController@audits')->middleware('can:planificare');
        Route::post('/add', 'CustomersController@store')->middleware('can:planificare');
        Route::prefix('/{customer}')->group(function() {
            Route::patch('/update', 'CustomersController@update')->middleware('can:planificare');
            Route::get('/destinations', 'DestinationController@fetch')->name('destination.fetch')->middleware('can:planificare');
            Route::post('/destinations/find', 'DestinationController@findOrNew')->name('destination.findOrNew')->middleware('can:planificare');
            Route::post('/destinations/search', 'DestinationController@search')->middleware('can:planificare');
        });
    });

    Route::prefix('/species')->group(function() {
        Route::get('/', 'SpeciesController@index');
        Route::get('/all', 'SpeciesController@fetchAll')->name('species.index')->middleware('can:planificare');
        Route::get('/fetch', 'SpeciesController@fetch')->name('species.fetch')->middleware('can:planificare');
        Route::get('/audits', 'SpeciesController@audits')->middleware('can:planificare');
        Route::post('/add', 'SpeciesController@store')->middleware('can:planificare');
        Route::patch('/{species}/update', 'SpeciesController@update')->middleware('can:planificare');
    });

    Route::prefix('/products')->group(function() {
        Route::get('/', 'ProductTypesController@index')->middleware('can:planificare');
        Route::get('/all', 'ProductTypesController@fetchAll')->name('products.index')->middleware('can:planificare');
        Route::get('/fetch', 'ProductTypesController@fetch')->name('products.fetch')->middleware('can:planificare');
        Route::get('/audits', 'ProductTypesController@audits')->middleware('can:planificare');
        Route::post('/add', 'ProductTypesController@store')->middleware('can:planificare');
        Route::patch('/{product}/update', 'ProductTypesController@update')->middleware('can:planificare');
    });

    Route::prefix('/quality')->group(function() {
        Route::get('/', 'QualityController@index')->middleware('can:planificare');
        Route::get('/all', 'QualityController@fetchAll')->name('quality.index')->middleware('can:planificare');
        Route::get('/fetch', 'QualityController@fetch')->name('quality.fetch')->middleware('can:planificare');
        Route::get('/audits', 'QualityController@audits')->middleware('can:planificare');
        Route::post('/add', 'QualityController@store')->middleware('can:planificare');
        Route::patch('/{quality}/update', 'QualityController@update')->middleware('can:planificare');
    });

    Route::prefix('/refinements')->group(function() {
        Route::get('/', 'RefinementsController@index')->middleware('can:planificare');
        Route::get('/all', 'RefinementsController@fetchAll')->name('refinements.index')->middleware('can:planificare');
        Route::get('/fetch', 'RefinementsController@fetch')->name('refinements.fetch')->middleware('can:planificare');
        Route::get('/audits', 'RefinementsController@audits')->middleware('can:planificare');
        Route::post('/add', 'RefinementsController@store')->middleware('can:planificare');
        Route::patch('/{refinement}/update', 'RefinementsController@update')->middleware('can:planificare');
    });

    Route::prefix('/articles')->group(function() {
        Route::get('/', 'ArticlesController@index')->middleware('can:planificare');
        Route::get('/all', 'ArticlesController@fetchAll')->name('articles.index')->middleware('can:planificare');
        Route::get('/fetch', 'ArticlesController@fetch')->name('articles.fetch')->middleware('can:planificare');
        Route::get('/audits', 'ArticlesController@audits')->middleware('can:planificare');
        Route::post('/add', 'ArticlesController@store')->middleware('can:planificare');
        Route::post('/import', 'ArticlesController@import')->middleware('can:planificare');
        Route::patch('/{article}/update', 'ArticlesController@update')->middleware('can:planificare');
    });

    Route::prefix('/numbers')->group(function() {
        Route::get('/', 'OrderNumbersController@index')->middleware('can:planificare');
        Route::get('/all', 'OrderNumbersController@fetchAll')->name('order_numbers.index')->middleware('can:planificare');
        Route::get('/audits', 'OrderNumbersController@audits')->middleware('can:planificare');
        Route::post('/add', 'OrderNumbersController@store')->middleware('can:planificare');
    });

    /*
     |---------------------------------------------------------------
     |  Orders routes
     |---------------------------------------------------------------
     */
    Route::prefix('/orders')->group(function () {
        Route::get('/', 'OrdersController@index')->middleware('can:sef_schimb');
        Route::get('/all', 'OrdersController@fetchAll')->name('orders.index')->middleware('can:sef_schimb');
        Route::get('/fetch', 'OrdersController@fetch')->name('orders.fetch')->middleware('can:planificare');
        Route::get('/audits', 'OrdersController@audits')->middleware('can:planificare');
        Route::get('/details/audits', 'OrderDetailsController@audits')->name('details.audits')->middleware('can:planificare');
        Route::post('/print/multiple', 'OrdersController@printMultiple')->middleware('can:planificare');
        Route::post('/add', 'OrdersController@store')->middleware('can:planificare');
        Route::prefix('/{order}/')->group(function() {
            Route::get('/show', 'OrdersController@show')->middleware('can:sef_schimb');
            Route::post('/copy', 'OrdersController@copy')->middleware('can:planificare');
            Route::get('/print/{orientation}', 'OrdersController@print')->middleware('can:planificare');
            Route::get('/export', 'OrdersController@export')->middleware('can:productie');
            Route::prefix('/documents')->group(function() {
                Route::get('/fetch', 'OrdersController@fetchAttachments')->middleware('can:productie');
                Route::post('/upload', 'OrdersController@uploadAttachment')->middleware('can:planificare');
                Route::delete('/{document}/delete', 'OrdersController@deleteAttachment')->middleware('can:planificare');
            });
            Route::prefix('/details')->group(function() {
                Route::get('/', 'OrderDetailsController@fetch')->middleware('can:sef_schimb');
                Route::post('/add', 'OrderDetailsController@store')->middleware('can:planificare');
                Route::post('/copy', 'OrderDetailsController@copy')->middleware('can:planificare');
                Route::delete('/package/delete', 'OrderDetailsController@destroyPackage')->middleware('can:planificare');
                Route::prefix('/{position}')->group(function() {
                    Route::get('/fetch', 'OrderDetailsController@getPosition')->middleware('can:planificare');
                    Route::patch('/update', 'OrderDetailsController@update')->middleware('can:planificare');
                    Route::delete('/delete', 'OrderDetailsController@destroyPosition')->middleware('can:planificare');
                });
            });
            Route::post('/fields', 'OrdersController@fields')->middleware('can:planificare');
            Route::prefix('/update')->group(function() {
                Route::patch('/', 'OrdersController@update')->middleware('can:planificare');
                Route::patch('/priority', 'OrdersController@setPriority')->middleware('can:planificare');
                Route::patch('/details', 'OrdersController@setDetails')->middleware('can:planificare');
                Route::patch('/observations', 'OrdersController@setObservations')->middleware('can:planificare');
                Route::patch('/dates', 'OrdersController@setDates')->middleware('can:planificare');
            });
            Route::patch('/ship', 'OrdersController@ship')->middleware('can:planificare');
            Route::patch('/ship/partial', 'OrdersController@shipPartial')->middleware('can:planificare');
        });
    });

    /*
     |---------------------------------------------------------------
     |  Orders archive routes
     |---------------------------------------------------------------
     */
    Route::prefix('/archive')->group(function() {
        Route::get('/', 'OrdersController@archive')->middleware('can:planificare');
        Route::get('/all', 'OrdersController@fetchAllArchive')->name('archive.index')->middleware('can:planificare');
    });


    /*
     |---------------------------------------------------------------
     |  Reports routes
     |---------------------------------------------------------------
     */
     Route::prefix('/reports')->group(function() {
        Route::get('/', 'ReportsController@index')->middleware('can:productie');
        Route::get('/archive', 'ReportsController@indexArchive')->middleware('can:productie');
        Route::get('archive/fetch', 'ReportsController@fetchArchive')->name('archive.fetch')->middleware('can:productie');
        Route::get('/active', 'ReportsController@exportActiveOrders')->middleware('can:sef_schimb');
        Route::get('/production/plan', 'ReportsController@exportProductionPlan')->middleware('can:productie');
        Route::get('/deliveries', 'ReportsController@exportDeliveriesDuringTimeRange')->middleware('can:planificare');
     });


    /*
     |---------------------------------------------------------------
     |  Import routes
     |---------------------------------------------------------------
     */
    Route::prefix('/import')->group(function () {
        Route::get('/', 'ReportsController@imports')->middleware('can:productie');
        Route::get('/archive', 'ReportsController@indexImports')->middleware('can:productie');
        Route::get('archive/fetch', 'ReportsController@fetchImports')->name('imports.fetch')->middleware('can:productie');
        Route::post('production/start', 'ReportsController@importProduction')->middleware('can:planificare');
        Route::post('production/plan/start', 'ReportsController@importProductionPlan')->middleware('can:productie');
        Route::post('deliveries/start', 'ReportsController@importDeliveries')->middleware('can:planificare');
    });

    /*
     |---------------------------------------------------------------
     |  User management routes
     |---------------------------------------------------------------
     */
    Route::prefix('/users')->group(function () {
        Route::get('/', 'UsersController@index')->middleware('can:administrator');
        Route::post('/add', 'UsersController@store')->middleware('can:administrator');
        Route::get('/all', 'UsersController@fetchAll')->name('users.index')->middleware('can:administrator');
        Route::get('/fetch', 'UsersController@fetch')->middleware('can:administrator');
        Route::prefix('/{id}')->group(function () {
            Route::patch('/update', 'UsersController@update')->middleware('can:administrator');
            Route::post('/activate', 'UsersController@activate')->middleware('can:administrator');
            Route::post('/deactivate', 'UsersController@deactivate')->middleware('can:administrator');
        });
    });

});
