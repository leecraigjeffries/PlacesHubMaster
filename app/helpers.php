<?php

    function typeSelect(array $types): array
    {
        foreach ($types as $type) {
            $options[$type] = __("places.{$type}");
        }

        return $options ?? [];
    }

    /**
     * @param array ...$array
     * @return stdClass|null
     */
    function array_to_object(array ...$array): ?stdClass
    {
        if(!$array){
            return null;
        }

        $obj = new stdClass;

        $merged = [[]];
        foreach ($array as $key => $value) {
            $merged[] = $value;
        }

        $merged = array_merge(...$merged);

        foreach ($merged as $key => $value) {
            $obj->{$key} = $value;
        }

        return $obj;
    }

    function collection_search_name($collection, string $name)
    {
        $result = $collection->search(function ($item, $key) use ($name) {
            return $item->name == $name;
        });

        if ($result !== false) {
            return $collection[$result];
        }

        return false;
    }

    /**
     * Translate an array.
     *
     * @param array $keys
     * @param string $prefix
     * @return array
     */
    function transArray(array $keys, string $prefix = 'placeshub'): array
    {
        foreach ($keys as $key){
            $langKey = str_replace('.', '_', $key);

            if($prefix !== ''){
                $langKey = "{$prefix}.{$langKey}";
            }

            $transArray[$key] = __($langKey);
        }

        return $transArray ?? [];
    }

    /**
     * @param string|null $name
     * @return string
     */
    function prepare_name_for_search(?string $name): string
    {
        if (!$name) {
            return '';
        }

        $name = str_replace(['.', '!', '-', ' '], ['', '', '%', '%'], $name);

        return "%{$name}%";
    }

    /**
     * Get distance between two points in kilometres
     *
     * @param float $lat_1
     * @param float $lon_1
     * @param float $lat_2
     * @param float $lon_2
     * @param string $units
     * @param int $precision
     *
     * @return float
     */
    function distance_between_coordinates(
        float $lat_1,
        float $lon_1,
        float $lat_2,
        float $lon_2,
        string $units = 'km',
        int $precision = 2
    ): float {
        $theta = $lon_1 - $lon_2;
        $distance = sin(deg2rad($lat_1)) * sin(deg2rad($lat_2)) + cos(deg2rad($lat_1)) * cos(deg2rad($lat_2)) * cos(deg2rad($theta));
        $distance = acos($distance);
        $distance = rad2deg($distance);

        switch ($units) {
            case 'km':
                $distance *= 111.18957696;
                break;
            case 'mi':
                $distance *= 69.05482;
                break;
        }

        return round($distance, $precision);
    }

    /**
     * Directory is empty.
     *
     * Credit to user: Your Common Sense
     * https://stackoverflow.com/questions/7497733/how-can-i-use-php-to-check-if-a-directory-is-empty
     *
     * @param string $dir
     * @return bool
     */
    function dir_is_empty(string $dir) {
        $handle = opendir($dir);

        while (false !== ($entry = readdir($handle))) {
            if ($entry !== '.' && $entry !== '..') {
                closedir($handle);
                return false;
            }
        }

        closedir($handle);

        return true;
    }

    /**
     * Description: The point-in-polygon algorithm allows you to check if a point is
     * inside a polygon or outside of it.
     * Author: Michaël Niessen (2009)
     * Website: http://AssemblySys.com
     *
     * If you find this script useful, you can show your
     * appreciation by getting Michaël a cup of coffee ;)
     * PayPal: https://www.paypal.me/MichaelNiessen
     *
     * As long as this notice (including author name and details) is included and
     * UNALTERED, this code is licensed under the GNU General Public License version 3:
     * http://www.gnu.org/licenses/gpl.html
     */
    class pointLocation
    {
        var $pointOnVertex = true; // Check if the point sits exactly on one of the vertices?

        function __construct()
        {
        }

        function pointInPolygon($point, $polygon, $pointOnVertex = true)
        {
            $this->pointOnVertex = $pointOnVertex;

            // Transform string coordinates into arrays with x and y values
            $point = $this->pointStringToCoordinates($point);
            $vertices = array();
            foreach ($polygon as $vertex) {
                $vertices[] = $this->pointStringToCoordinates($vertex);
            }

            // Check if the point sits exactly on a vertex
            if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
                return "vertex";
            }

            // Check if the point is inside the polygon or on the boundary
            $intersections = 0;
            $vertices_count = count($vertices);

            for ($i = 1; $i < $vertices_count; $i++) {
                $vertex1 = $vertices[$i - 1];
                $vertex2 = $vertices[$i];
                if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'],
                        $vertex2['x']) and $point['x'] < max($vertex1['x'],
                        $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
                    return "boundary";
                }
                if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'],
                        $vertex2['y']) and $point['x'] <= max($vertex1['x'],
                        $vertex2['x']) and $vertex1['y'] != $vertex2['y']) {
                    $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
                    if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
                        return "boundary";
                    }
                    if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                        $intersections++;
                    }
                }
            }
            // If the number of edges we passed through is odd, then it's in the polygon.
            if ($intersections % 2 != 0) {
                return "inside";
            } else {
                return "outside";
            }
        }

        function pointOnVertex($point, $vertices)
        {
            foreach ($vertices as $vertex) {
                if ($point == $vertex) {
                    return true;
                }
            }

        }

        function pointStringToCoordinates($pointString)
        {
            $coordinates = explode(" ", $pointString);
            return array("x" => $coordinates[0], "y" => $coordinates[1]);
        }

    }