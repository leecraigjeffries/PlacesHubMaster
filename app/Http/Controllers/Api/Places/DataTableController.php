<?php

    namespace App\Http\Controllers\Api\Places;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Api\Places\IndexRequest;
    use App\Http\Resources\Api\Places\DataTableCollection;
    use App\Models\Place;
    use App\Repositories\PlaceRepository;

    class DataTableController extends Controller
    {
        /**
         * @param Place $place
         * @param PlaceRepository $repo
         * @param IndexRequest $request
         * @return DataTableCollection
         */
        public function index(Place $place, PlaceRepository $repo, IndexRequest $request): DataTableCollection
        {
            $query = $repo->dataTable($place, $request->input('type'));

            return new DataTableCollection($query);
        }

    }