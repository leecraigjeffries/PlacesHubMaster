<?php

    /** @var Factory $factory */

    use App\Models\Import\GeoPlace;
    use App\Models\Import\OnsPlace;
    use App\Services\Import\Importers\Places\GeoImportService;
    use App\Services\Import\Importers\Places\OnsImportService;
    use Faker\Generator as Faker;
    use Illuminate\Database\Eloquent\Factory;


    $factory->define(OnsPlace::class, static function (Faker $faker) {
        return [
            'name' => $faker->city,
            'type' => array_rand(app(OnsImportService::class)->getValidTypes()),
            'lat' => random_int(-90, 90) / random_int(-90, 90),
            'lon' => random_int(-180, 180) / random_int(-180, 180)
        ];
    });
