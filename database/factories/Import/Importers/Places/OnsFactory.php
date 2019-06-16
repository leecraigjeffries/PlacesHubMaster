<?php

    /** @var Factory $factory */

    use App\Models\Imports\OnsPlace;
    use App\Services\Imports\Importers\Places\OnsImportService;
    use Faker\Generator as Faker;
    use Illuminate\Database\Eloquent\Factory;


    $factory->define(OnsPlace::class, static function (Faker $faker) {
        return [
            'name' => $faker->city,
            'ipn_id' => 'IPN' . sprintf('%07d', random_int(1, 999999)),
            'type' => array_rand(app(OnsImportService::class)->getValidTypes())
        ] + randomCoords(true);
    });
