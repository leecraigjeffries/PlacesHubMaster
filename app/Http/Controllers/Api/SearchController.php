<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Api\Search\ShowRequest;
    use App\Http\Resources\Api\Search\ShowResourceCollection;
    use App\Models\Place;

    class SearchController extends Controller
    {
        private $place;

        public function __construct(Place $place)
        {
            $this->place = $place;
        }

        public function show(ShowRequest $request): ShowResourceCollection
        {
            $types = $this->place::typesWithoutLastElement();

            $results = $this->place->select(array_merge(['id', 'name', 'type', 'slug'], $this->place::typesWithoutLastElement(true)))
                ->where('name', 'like', prepare_name_for_search($request->input('name')))
                ->orWhere('official_name', 'like', prepare_name_for_search($request->input('name')))
                ->orderBy('name')
                ->limit(10);

            foreach ($types as $type) {
                $results = $results->with([
                    $type => static function ($query) {
                        return $query->select(['id', 'name', 'type', 'slug']);
                    }
                ]);
            }

            return new ShowResourceCollection($results->get());
        }
    }
