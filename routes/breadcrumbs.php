<?php

    Breadcrumbs::for('home', static function ($trail) {
        $trail->push(__('placeshub.home'), route('home'));
    });

    Breadcrumbs::for('places.show', static function ($trail, $place) {
        foreach ($place::getTypes() as $type) {
            if ($place->{$type . '_id'}) {
                $trail->push($place->{$type}->name, route('places.show', $place->{$type}));
            }
        }

        $trail->push($place->name);
    });

    Breadcrumbs::for('places.store', static function ($trail, $place) {
        $trail->parent('home');

        foreach ($place->getTypes() as $type) {
            if ($place->{$type . '_id'}) {
                $trail->push($place->{$type}->name, route('places.show', $place->{$type}));
            }
        }

        $trail->push($place->name, route('places.show', $place));
        $trail->push(__('placeshub.summary'));
    });

    Breadcrumbs::for('places.create', static function ($trail, $place, $type) {
        foreach ($place->seniorTypes() as $seniorType) {
            if ($place->{$seniorType . '_id'}) {
                $trail->push($place->{$seniorType}->name, route('places.show', $place->{$seniorType}));
            }
        }

        $trail->push($place->name, route('places.show', $place));
        $trail->push(__('placeshub.create_type', ['type' => __("placeshub.{$type}")]));
    });

    /**
     * Admin
     */
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

    Breadcrumbs::for('admin.imports.places.os.create', static function ($trail) {
        $trail->parent('admin.home');
        $trail->push(__('admin.import_os_places'));
    });

    Breadcrumbs::for('admin.imports.places.os.store', static function ($trail) {
        $trail->parent('admin.home');
        $trail->push(__('admin.import_os_places'));
    });

    Breadcrumbs::for('admin.imports.places.osm.create', static function ($trail) {
        $trail->parent('admin.home');
        $trail->push(__('admin.import_osm_places'));
    });

    Breadcrumbs::for('admin.imports.places.osm.store', static function ($trail) {
        $trail->parent('admin.home');
        $trail->push(__('admin.import_osm_places'));
    });

    /**
     * Imports
     */
    Breadcrumbs::for('admin.imports.places.geo.index', static function ($trail) {
        $trail->push(__('placeshub.geo_data'), route('admin.imports.places.geo.index'));
    });

    Breadcrumbs::for('admin.imports.places.geo.show', static function ($trail, $place) {
        $trail->parent('admin.imports.places.geo.index');

        foreach ($place->getAdminTypes() as $type) {
            if ($place->{$type . '_id'} && $place->{$type . '_id'} !== $place->id) {
                $trail->push($place->{$type}->name, route('admin.imports.places.geo.show', $place->{$type}));
            }
        }
        $trail->push($place->name);
    });

    Breadcrumbs::for('admin.imports.places.ons.index', static function ($trail) {
        $trail->push(__('placeshub.ons_data'), route('admin.imports.places.ons.index'));
    });

    Breadcrumbs::for('admin.imports.places.ons.show', static function ($trail, $place) {
        $trail->parent('admin.imports.places.ons.index');

        foreach ($place->getAdminTypes() as $type) {
            if ($place->{$type . '_id'} && $place->{$type . '_id'} !== $place->id) {
                $trail->push($place->{$type}->name, route('admin.imports.places.ons.show', $place->{$type}));
            }
        }
        $trail->push($place->name);
    });

    Breadcrumbs::for('admin.imports.places.os.index', static function ($trail) {
        $trail->push(__('placeshub.os_data'), route('admin.imports.places.os.index'));
    });

    Breadcrumbs::for('admin.imports.places.os.show', static function ($trail, $place) {
        $trail->parent('admin.imports.places.os.index');

        foreach ($place->getAdminTypes() as $type) {
            if ($place->{$type . '_id'} && $place->{$type . '_id'} !== $place->id) {
                $trail->push($place->{$type}->name, route('admin.imports.places.os.show', $place->{$type}));
            }
        }
        $trail->push($place->name);
    });

    Breadcrumbs::for('admin.imports.places.osm.index', static function ($trail) {
        $trail->push(__('placeshub.osm_data'), route('admin.imports.places.osm.index'));
    });

    Breadcrumbs::for('admin.imports.places.osm.show', static function ($trail, $place) {
        $trail->parent('admin.imports.places.osm.index');

        $trail->push($place->name);
    });