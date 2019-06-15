<?php

    Breadcrumbs::for('home', static function ($trail) {
        $trail->push(__('placeshub.home'), route('home'));
    });

    Breadcrumbs::for('places.show', static function ($trail, $place) {
        foreach ($place->types() as $type) {
            if ($place->{$type . '_id'}) {
                $trail->push($place->{$type}->name, route('places.show', $place->{$type}));
            }
        }

        $trail->push($place->name);
    });

    Breadcrumbs::for('admin.home', static function ($trail) {
        $trail->push(__('admin.home'), route('admin.home'));
    });

    Breadcrumbs::for('admin.import.geo-places.create', static function ($trail) {
        $trail->parent('admin.home');
        $trail->push(__('admin.import_geo_places'));
    });

    Breadcrumbs::for('admin.import.geo-places.store', static function ($trail) {
        $trail->parent('admin.home');
        $trail->push(__('admin.import_geo_places'));
    });

    Breadcrumbs::for('admin.import.ons-places.create', static function ($trail) {
        $trail->parent('admin.home');
        $trail->push(__('admin.import_ons_places'));
    });

    Breadcrumbs::for('admin.import.ons-places.store', static function ($trail) {
        $trail->parent('admin.home');
        $trail->push(__('admin.import_ons_places'));
    });

    Breadcrumbs::for('import.places.geo.index', static function ($trail) {
        $trail->parent('home');
        $trail->push(__('placeshub.geo_data'), route('import.geo-places.index'));
    });