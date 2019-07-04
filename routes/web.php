<?php


    use App\Models\Place;

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
                Route::group(['prefix' => 'places', 'as' => 'places.', 'namespace' => 'Places'],
                    static function () {
                        // Geonames
                        Route::group(['prefix' => 'geo', 'as' => 'geo.'],
                            static function () {
                                Route::post('', [
                                    'uses' => 'GeoController@store',
                                    'as' => 'store'
                                ]);
                                Route::get('', [
                                    'uses' => 'GeoController@create',
                                    'as' => 'create'
                                ]);
                                Route::get('', [
                                    'uses' => 'GeoController@index',
                                    'as' => 'index'
                                ]);
                                Route::get('{geoPlace}', [
                                    'uses' => 'GeoController@show',
                                    'as' => 'show'
                                ]);
                            });

                        // ONS
                        Route::group(['prefix' => 'ons', 'as' => 'ons.'],
                            static function () {
                                Route::post('', [
                                    'uses' => 'OnsController@store',
                                    'as' => 'store'
                                ]);
                                Route::get('create', [
                                    'uses' => 'OnsController@create',
                                    'as' => 'create'
                                ]);
                                Route::get('', [
                                    'uses' => 'OnsController@index',
                                    'as' => 'index'
                                ]);
                                Route::get('{onsPlace}', [
                                    'uses' => 'OnsController@show',
                                    'as' => 'show'
                                ]);
                                Route::get('ipn-id/{ipnId}', [
                                    'uses' => 'OnsController@showIpnId',
                                    'as' => 'show-ipn-id',
                                    'where' => ['ipn_id' => 'IPN[0-9]{7}']
                                ]);
                                Route::get('ons-id/{onsId}', [
                                    'uses' => 'OnsController@showOnsId',
                                    'as' => 'show-ons-id',
                                    'where' => ['ons_id' => 'E[0-9]{8}']
                                ]);
                            });

                        // OS
                        Route::group(['prefix' => 'os', 'as' => 'os.'],
                            static function () {
                                Route::post('', [
                                    'uses' => 'OsController@store',
                                    'as' => 'store'
                                ]);
                                Route::get('create', [
                                    'uses' => 'OsController@create',
                                    'as' => 'create'
                                ]);
                                Route::get('', [
                                    'uses' => 'OsController@index',
                                    'as' => 'index'
                                ]);
                                Route::get('{osPlace}', [
                                    'uses' => 'OsController@show',
                                    'as' => 'show',
                                    'where' => [
                                        'osPlace' => '(.*)'
                                    ]
                                ]);
                            });

                        // OSM
                        Route::group(['prefix' => 'osm', 'as' => 'osm.'],
                            static function () {
                                Route::post('', [
                                    'uses' => 'OsmController@store',
                                    'as' => 'store'
                                ]);
                                Route::get('create', [
                                    'uses' => 'OsmController@create',
                                    'as' => 'create'
                                ]);
                                Route::get('', [
                                    'uses' => 'OsmController@index',
                                    'as' => 'index'
                                ]);
                                Route::get('{osmPlace}', [
                                    'uses' => 'OsmController@show',
                                    'as' => 'show',
                                    'where' => [
                                        'osmPlace' => '(.*)'
                                    ]
                                ]);
                            });
                    });
            });
        });

    /**
     * Places
     */
    Route::group(['prefix' => 'places', 'as' => 'places.', 'namespace' => 'Places'], static function () {
        Route::get('{place}', ['uses' => 'PlacesController@show', 'as' => 'show']);
        Route::get('', ['uses' => 'PlacesController@index', 'as' => 'index']);
        Route::get('{place}/edit', ['uses' => 'PlacesController@edit', 'as' => 'edit', 'middleware' => 'role:mod']);

        Route::group(['middleware' => 'role:mod'], static function () {

            Route::patch('{place}', [
                'uses' => 'PlacesController@update',
                'as' => 'update'
            ]);
            Route::delete('{place}', [
                'uses' => 'PlacesController@destroy',
                'as' => 'destroy'
            ]);
            Route::get('{place}/create/{type}', [
                    'uses' => 'PlacesController@create',
                    'as' => 'create',
                    'where' => [
                        'type' => implode('|', Place::getTypes())
                    ],
                    'middleware' => 'role:mod'
                ]
            );
            Route::post('places/{place}/create/{type}', [
                    'uses' => 'PlacesController@store',
                    'as' => 'store',
                    'where' => [
                        'type' => implode('|', Place::getTypes())
                    ]
                ]
            );

            Route::group(['prefix' => 'move', 'as' => 'move.'], static function () {
                Route::get('edit', ['uses' => 'MoveController@edit', 'as' => 'edit']);
            });

            Route::group(['prefix' => 'move-children', 'as' => 'move-children.'], static function () {
                Route::get('edit', ['uses' => 'MoveChildrenController@edit', 'as' => 'edit']);
            });

            Route::group(['prefix' => 'change-type', 'as' => 'change-type.'], static function () {
                Route::get('edit', ['uses' => 'ChangeTypeController@edit', 'as' => 'edit']);
            });
        });
    });

    /**
     * Import
     */
    Route::group(['prefix' => 'imports', 'as' => 'imports.', 'namespace' => 'Imports'],
        static function () {
        });

    Route::group(['prefix' => 'imports', 'as' => 'imports.', 'namespace' => 'Imports'],
        static function () {
        });

    Route::group(['prefix' => 'imports', 'as' => 'imports.', 'namespace' => 'Imports'],
        static function () {
        });