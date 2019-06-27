<?php

    /** @var Factory $factory */

    use App\Models\Imports\GeoPlace;
    use App\Services\Imports\Importers\Places\GeoImportService;
    use Faker\Generator as Faker;
    use Illuminate\Database\Eloquent\Factory;

    $factory->define(GeoPlace::class, static function (Faker $faker) {
        return [
            'name' => $faker->city,
            'type' => array_rand(app(GeoImportService::class)->getValidTypes())
        ] + randomCoords(true);
    });
