<?php

    namespace App\Http\Controllers\Api\Admin\Imports\Places;

    use App\Http\Controllers\Controller;
    use App\Http\Resources\Admin\Imports\Places\OsDataTableCollection;
    use App\Models\Imports\OsPlace;

    class OsController extends Controller
    {
        private $model;

        public function __construct(OsPlace $model)
        {
            $this->model = $model;
        }

        public function dataTable(OsPlace $osPlace, string $placesHubOsType): OsDataTableCollection
        {
            $osPlaces = $this->model
                ->whereType($placesHubOsType)
                ->where($osPlace->type_column, $osPlace->os_id);

            foreach($osPlace->juniorAdminTypes() as $juniorAdminType){
                $osPlaces = $osPlaces->whereNull($juniorAdminType . '_id');
            }

            return new OsDataTableCollection($osPlaces->get());
        }
    }
