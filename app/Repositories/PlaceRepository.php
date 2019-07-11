<?php

    namespace App\Repositories;

    use App\Http\Requests\Api\ShowRequest;
    use App\Http\Requests\Places\IndexRequest;
    use App\Models\Place;
    use Illuminate\Contracts\Pagination\LengthAwarePaginator;
    use Illuminate\Support\Collection;

    class PlaceRepository
    {
        private $model;

        public function __construct(Place $model)
        {
            $this->model = $model;
        }

        /**
         * @param IndexRequest $request
         *
         * @return LengthAwarePaginator
         */
        public function search(IndexRequest $request): LengthAwarePaginator
        {
            $query = $this->model::with($this->model::types())
                ->orderBy($request->input('orderby') ?: 'name');

            foreach ($this->model::types() as $type) {
                if ($request->{$type . '_name'}) {
                    $query->whereHas($type, static function ($query) use ($request, $type) {
                        $query->where('name', 'LIKE', prepare_name_for_search($request->{$type . '_name'}));
                    });
                }
            }

            if ($request->name) {
                $query = $query->where('name', 'LIKE', prepare_name_for_search($request->name));
            }

            if ($request->type) {
                $query = $query->where(static function ($query) use ($request) {
                    return $query->where('type', $request->type)
                        ->orWhere('type_2', $request->type);
                });
            }

            return $query->paginate(30);
        }


        /**
         * @param Place $place
         * @param string $type
         *
         * @return Collection
         */
        public function dataTable(Place $place, string $type): Collection
        {
            $places = $this->model
                ->whereType($type)
                ->where($place->type_column, $place->id);

            foreach ($place->juniorColumns() as $col) {
                $places = $places->whereNull($col);
            }

            return $places->get([
                'name',
                'slug',
                'wiki_title',
                'wikidata_id',
                'osm_id',
                'ons_id',
                'os_id',
                'ipn_id',
                'geo_id',
                'geo_id_2',
                'geo_id_3',
                'geo_id_4',
                'lat',
                'lon',
                'approved_at',
            ]);
        }
    }