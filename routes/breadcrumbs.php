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

    Breadcrumbs::for('admin.imports.places.geo.create', static function ($trail) {
        $trail->parent('admin.home');
        $trail->push(__('admin.import_geo_places'));
    });

    Breadcrumbs::for('admin.imports.places.geo.store', static function ($trail) {
        $trail->parent('admin.home');
        $trail->push(__('admin.import_geo_places'));
    });

    Breadcrumbs::for('admin.imports.places.ons.create', static function ($trail) {
        $trail->parent('admin.home');
        $trail->push(__('admin.import_ons_places'));
    });

    Breadcrumbs::for('admin.imports.places.ons.store', static function ($trail) {
        $trail->parent('admin.home');
        $trail->push(__('admin.import_ons_places'));
    });

    Breadcrumbs::for('imports.places.geo.index', static function ($trail) {
        $trail->push(__('placeshub.geo_data'), route('imports.places.geo.index'));
    });

    Breadcrumbs::for('imports.places.geo.show', static function ($trail, $place) {
        $trail->parent('imports.places.geo.index');

        foreach ($place->getAdminTypes() as $type) {
            if ($place->{$type . '_id'} && $place->{$type . '_id'} !== $place->id) {
                $trail->push($place->{$type}->name, route('imports.places.geo.show', $place->{$type}));
            }
        }
        $trail->push($place->name);
    });