<?php

    use Grimzy\LaravelMysqlSpatial\Types\Point;

    /**
     * @param bool $withPoint
     * @return array
     * @throws Exception
     */
    function randomCoords(bool $withPoint = false):array
    {
        $x = random_int(-90, 90);

        if($x === 0){
            $x = 1;
        }

        $lat = random_int(-90, 90) / $x;
        $lon = random_int(-180, 180) / $x * 2;

        if($withPoint === true){
            return compact('lat', 'lon') + ['point' => new Point($lat, $lon)];
        }

        return compact('lat', 'lon');

    }