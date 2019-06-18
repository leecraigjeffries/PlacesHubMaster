<?php

    /** @var Factory $factory */

    use App\Models\Imports\OsPlace;
    use App\Services\Imports\Importers\Places\OsImportService;
    use Faker\Generator as Faker;
    use Illuminate\Database\Eloquent\Factory;


    $factory->define(OsPlace::class, static function (Faker $faker) {
        return [
            'name' => $faker->city,
//            'type' => array_rand(app(OsImportService::class)->getValidTypes())
        ] + randomCoords(true);
    });
