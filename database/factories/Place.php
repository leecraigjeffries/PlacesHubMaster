<?php

    /** @var Factory $factory */

    use App\Models\Place;
    use Faker\Generator as Faker;
    use Illuminate\Database\Eloquent\Factory;

    $factory->define(Place::class, static function (Faker $faker) {
        return [
                'name' => $faker->city,
            ] + randomCoords(true);
    });
