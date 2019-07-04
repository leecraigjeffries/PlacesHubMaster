<?php

    Route::group(['as' => 'api.', 'namespace' => 'Api'],
        static function () {

            Route::get('search', [
                'uses' => 'SearchController@show',
                'as' => 'search.show'
            ]);

            Route::get('data-tables/{place}', [
                'uses' => 'Places\DataTableController@index',
                'as' => 'places.data-table.index'
            ]);

            Route::get('places/approve/{place}', [
                'uses' => 'PlacesController@approve',
                'as' => 'places.approve',
                'middleware' => ['auth:api', 'role:mod']
            ]);

            Route::get('extractor/wikipedia', [
                'uses' => 'Extractors\WikiController@infobox',
                'as' => 'extractor.wikipedia-titles',
//                'middleware' => ['auth:api', 'role:mod'],
            ]);

            Route::get('admin/imports/places/geo/{geoPlace}/{placesHubGeoType}', [
                'uses' => 'Admin\Imports\Places\GeoController@dataTable',
                'as' => 'admin.imports.places.geo.data-table'
            ]);

            Route::get('admin/imports/places/ons/{onsPlace}/{placesHubOnsType}', [
                'uses' => 'Admin\Imports\Places\OnsController@dataTable',
                'as' => 'admin.imports.places.ons.data-table'
            ]);

            Route::get('admin/imports/places/os/{osPlace}/{placesHubOsType}', [
                'uses' => 'Admin\Imports\Places\OsController@dataTable',
                'as' => 'admin.imports.places.os.data-table'
            ]);

        });