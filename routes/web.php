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

            /**
             * Imports
             */
            Route::group(['prefix' => 'dumps', 'as' => 'dumps.', 'namespace' => 'Dumps'], static function () {
                Route::get('places', ['uses' => 'PlacesController@create', 'as' => 'places.create']);
                Route::post('places', ['uses' => 'PlacesController@store', 'as' => 'places.store']);

            });


            /**
             * Imports
             */
            Route::group(['prefix' => 'imports', 'as' => 'imports.', 'namespace' => 'Imports'], static function () {
                Route::group(['prefix' => 'local-admins', 'as' => 'local-admins.', 'namespace' => 'LocalAdmins'],
                    static function () {
                        // ONS
                        Route::group(['prefix' => 'ons', 'as' => 'ons.'], static function () {
                            Route::post('', [
                                'uses' => 'OnsController@store',
                                'as' => 'store'
                            ]);
                            Route::get('create', [
                                'uses' => 'OnsController@create',
                                'as' => 'create'
                            ]);
                            Route::get('compare', [
                                'uses' => 'OnsController@compare',
                                'as' => 'compare'
                            ]);
                        });
                    });
                Route::group(['prefix' => 'places', 'as' => 'places.', 'namespace' => 'Places'], static function () {
                    // Geonames
                    Route::group(['prefix' => 'geo', 'as' => 'geo.'], static function () {
                        Route::post('', [
                            'uses' => 'GeoController@store',
                            'as' => 'store'
                        ]);
                        Route::get('create', [
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
                    Route::group(['prefix' => 'ons', 'as' => 'ons.'], static function () {
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
                    Route::group(['prefix' => 'os', 'as' => 'os.'], static function () {
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
                    Route::group(['prefix' => 'osm', 'as' => 'osm.'], static function () {
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
            /**
             * Type
             */
            Route::group(['prefix' => 'type', 'as' => 'type.'], static function () {
                Route::get('change-type/{place}', ['uses' => 'TypeController@edit', 'as' => 'edit']);
                Route::post('promote/{place}', ['uses' => 'TypeController@promote', 'as' => 'promote']);
                Route::post('demote/{place}', ['uses' => 'TypeController@demote', 'as' => 'demote']);
            });


            /**
             * Move
             */
            Route::group(['prefix' => 'move', 'as' => 'move.'], static function () {
                Route::get('{place}', ['uses' => 'MoveController@selectType', 'as' => 'select-type']);
                Route::patch('{place}/{type}/{destination}', ['uses' => 'MoveController@update', 'as' => 'update']);
            });

            /**
             * Move Children
             */
            Route::group(['prefix' => 'move-children', 'as' => 'move-children.'], static function () {
                Route::get('select-type/{place}', ['uses' => 'MoveChildrenController@selectType', 'as' => 'select-type']);
                Route::get('select-parent/{place}', ['uses' => 'MoveChildrenController@selectParent', 'as' => 'select-parent']);
                Route::patch('{place}/{destination}/{type}', ['uses' => 'MoveChildrenController@update', 'as' => 'update']);
            });

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
                        'type' => implode('|', Place::types())
                    ],
                    'middleware' => 'role:mod'
                ]
            );
            Route::post('places/{place}/create/{type}', [
                    'uses' => 'PlacesController@store',
                    'as' => 'store',
                    'where' => [
                        'type' => implode('|', Place::types())
                    ]
                ]
            );
        });
    });