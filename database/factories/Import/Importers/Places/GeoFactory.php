<?php

    /** @var \Illuminate\Database\Eloquent\Factory $factory */

    use App\Models\Import\GeoPlace;
    use Faker\Generator as Faker;

    function randomChars($numChars = 5)
    {
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle($str), 0, $numChars);
    }

    $factory->define(GeoPlace::class, static function (Faker $faker) {
        return [
            'name' => $faker->city,
            'type' => randomChars(5),
            'lat' => random_int(-90, 90) / random_int(-90, 90),
            'lon' => random_int(-180, 180) / random_int(-180, 180)
        ];
    });
