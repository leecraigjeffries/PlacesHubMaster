<?php

    Route::group(['as' => 'api.', 'namespace' => 'Api'],
        static function () {

            Route::get('search', [
                'uses' => 'SearchController@show',
                'as' => 'search.show'
            ]);

            Route::get('data-tables/{place}/{type}', [
                'uses' => 'PlacesController@dataTable',
                'as' => 'places.data-table'
            ]);

            Route::get('places/approve/{place}', [
                'uses' => 'PlacesController@approve',
                'as' => 'places.approve',
                'middleware' => ['auth:api', 'role:mod']
            ]);

            Route::get('extractor/wikipedia', [
                'uses' => 'ExtractorController@wikipediaTitles',
                'as' => 'extractor.wikipedia-titles',
                'middleware' => 'auth',
            ]);

        });