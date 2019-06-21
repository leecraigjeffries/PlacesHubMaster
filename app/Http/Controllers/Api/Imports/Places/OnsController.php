<?php

    namespace App\Http\Controllers\Api\Imports\Places;

    use App\Http\Controllers\Controller;
    use App\Http\Resources\Imports\Places\OnsDataTableCollection;
    use App\Models\Imports\OnsPlace;

    class OnsController extends Controller
    {
        private $model;

        public function __construct(OnsPlace $model)
        {
            $this->model = $model;
        }

        public function dataTable(OnsPlace $onsPlace, string $placesHubOnsType): OnsDataTableCollection
        {
            $onsPlaces = $this->model
                ->whereType($placesHubOnsType)
                ->where($onsPlace->type_column, $onsPlace->ons_id);

            foreach($onsPlace->juniorAdminTypes() as $juniorAdminType){
                $onsPlaces = $onsPlaces->whereNull($juniorAdminType . '_id');
            }

            return new OnsDataTableCollection($onsPlaces->get());
        }
    }
