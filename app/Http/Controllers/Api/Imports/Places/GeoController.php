<?php

    namespace App\Http\Controllers\Api\Imports\Places;

    use App\Http\Resources\Imports\Places\GeoDataTableCollection;
    use App\Models\Imports\GeoPlace;
    use App\Http\Controllers\Controller;
    use Illuminate\Support\Collection;

    class GeoController extends Controller
    {
        private $model;

        public function __construct(GeoPlace $model)
        {
            $this->model = $model;
        }

        public function dataTable(GeoPlace $geoPlace, string $placesHubGeoType)
        {
//            dd($placesHubGeoType);
            $geoPlaces = $this->model
                ->whereType($placesHubGeoType)
                ->where($geoPlace->type_column, $geoPlace->id);

            return new GeoDataTableCollection($geoPlaces->get());
        }
    }
