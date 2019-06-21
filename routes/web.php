<?php


    Route::get('/', static function () {
        return view('welcome');
    });

    Auth::routes();

    Route::get('/home', 'HomeController@index')->name('home');

    /**
     * Admin
     */
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => 'role:admin'],
        static function () {
            Route::get('', ['uses' => 'AdminController@home', 'as' => 'home']);

            Route::group(['prefix' => 'imports', 'as' => 'imports.', 'namespace' => 'Imports'], static function () {
                // Geonames
                Route::post('places/geo',
                    ['uses' => 'Places\GeoController@store', 'as' => 'places.geo.store']);
                Route::get('places/geo',
                    ['uses' => 'Places\GeoController@create', 'as' => 'places.geo.create']);

                // ONS
                Route::post('places/ons',
                    ['uses' => 'Places\OnsController@store', 'as' => 'places.ons.store']);
                Route::get('places/ons',
                    ['uses' => 'Places\OnsController@create', 'as' => 'places.ons.create']);

                // OS
                Route::post('places/os',
                    ['uses' => 'Places\OsController@store', 'as' => 'places.os.store']);
                Route::get('places/os',
                    ['uses' => 'Places\OsController@create', 'as' => 'places.os.create']);
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

    /**
     * Import
     */
    Route::group(['prefix' => 'imports', 'as' => 'imports.', 'namespace' => 'Imports'],
        static function () {
            Route::get('places/geo', ['uses' => 'Places\GeoController@index', 'as' => 'places.geo.index']);
            Route::get('places/geo/{geoPlace}', ['uses' => 'Places\GeoController@show', 'as' => 'places.geo.show']);
        });

    Route::group(['prefix' => 'imports', 'as' => 'imports.', 'namespace' => 'Imports'],
        static function () {
            Route::get('places/ons', ['uses' => 'Places\OnsController@index', 'as' => 'places.ons.index']);
            Route::get('places/ons/{onsPlace}', ['uses' => 'Places\OnsController@show', 'as' => 'places.ons.show']);
        });

    Route::group(['prefix' => 'imports', 'as' => 'imports.', 'namespace' => 'Imports'],
        static function () {
            Route::get('places/os', ['uses' => 'Places\OsController@index', 'as' => 'places.os.index']);

            /**
             * Thanks to username Gadoma
             * https://stackoverflow.com/questions/21552604/how-to-define-a-laravel-route-with-a-parameter-that-contains-a-slash-character
             */
            Route::get('places/os/{osPlace}', ['uses' => 'Places\OsController@show', 'as' => 'places.os.show', 'where' => [
                'osPlace' => '(.*)']]);
//            ]])->where('osPlace', '(.*)');
        });