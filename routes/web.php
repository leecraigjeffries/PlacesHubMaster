<?php

    Route::get('/', function () {
        return view('welcome');
    });

    Auth::routes();

    Route::get('/home', 'HomeController@index')->name('home');

    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => 'role:admin'],
        static function () {
            Route::get('', ['uses' => 'AdminController@home', 'as' => 'home']);

            Route::group(['prefix' => 'import', 'as' => 'import.', 'namespace' => 'Import'], static function () {
                // Geonames
                Route::post('geo-places',
                    ['uses' => 'GeoPlacesController@store', 'as' => 'geo-places.store']);
                Route::get('geo-places',
                    ['uses' => 'GeoPlacesController@create', 'as' => 'geo-places.create']);

                // ONS
                Route::post('ons-places',
                    ['uses' => 'OnsPlacesController@store', 'as' => 'ons-places.store']);
                Route::get('ons-places',
                    ['uses' => 'OnsPlacesController@create', 'as' => 'ons-places.create']);
            });
        });

    /**
     * Places
     */
    Route::group(['prefix' => 'places', 'as' => 'places.', 'namespace' => 'Places'],
        static function () {
            Route::get('{place}', ['uses' => 'PlacesController@show', 'as' => 'show']);
            Route::get('', ['uses' => 'PlacesController@index', 'as' => 'index']);
        });