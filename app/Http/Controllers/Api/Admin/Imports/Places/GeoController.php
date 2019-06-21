<?php

    namespace App\Http\Controllers\Api\Admin\Imports\Places;

    use App\Http\Controllers\Controller;
    use App\Http\Resources\Admin\Imports\Places\GeoDataTableCollection;
    use App\Models\Imports\GeoPlace;

    class GeoController extends Controller
    {
        private $model;

        public function __construct(GeoPlace $model)
        {
            $this->model = $model;
        }

        public function dataTable(GeoPlace $geoPlace, string $placesHubGeoType): GeoDataTableCollection
        {
            $geoPlaces = $this->model
                ->whereType($placesHubGeoType)
                ->where($geoPlace->type_column, $geoPlace->id);

            foreach($geoPlace->juniorAdminTypes() as $juniorAdminType){
                $geoPlaces = $geoPlaces->whereNull($juniorAdminType . '_id');
            }

            return new GeoDataTableCollection($geoPlaces->get());
        }
    }
