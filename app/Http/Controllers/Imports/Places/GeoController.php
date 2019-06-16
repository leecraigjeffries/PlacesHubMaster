<?php

    namespace App\Http\Controllers\Imports\Places;

    use App\Http\Controllers\Controller;
    use App\Http\Controllers\Import\Places\Geo\IndexRequest;
    use App\Models\Imports\GeoPlace;
    use App\Services\Imports\Search\Places\GeoSearch;
    use Illuminate\View\View;

    class GeoController extends Controller
    {
        /**
         * @param GeoPlace $geoPlace
         * @param IndexRequest $request
         * @return View
         */
        public function index(GeoPlace $geoPlace, IndexRequest $request):View
        {
            $geoSearch = app(GeoSearch::class, ['inputs' => $request->all()]);

            $results = $geoPlace
                ->select(['geo_places.*'])
                ->orderBy($geoSearch->getOrderBy(), $geoSearch->getOrder())
                ->when($geoSearch->getOrderBy() !== $geoSearch->getDefaultOrderBy(), static function ($query) use ($geoSearch) {
                    $query->orderBy($geoSearch->getDefaultOrderBy(), $geoSearch->getDefaultOrder());
                })
                ->when($request->input('name') !== null, static function ($query) use ($request) {
                    $query->where('geo_places.name', 'like', '%' . $request->input('name') . '%');
                })
                ->when($request->input('geo_code') !== null, static function ($query) use ($request) {
                    $query->where('geo_places.geo_code', 'like', '%' . $request->input('geo_code') . '%');
                })
                ->when($request->input('geo_code_full') !== null, static function ($query) use ($request) {
                    $query->where('geo_places.geo_code_full', 'like', '%' . $request->input('geo_code_full') . '%');
                })
                ->when($request->input('geo_type') !== null, static function ($query) use ($request) {
                    $query->where('geo_places.geo_type', $request->input('geo_type'));
                })
                ->with($geoPlace->getAdminTypes());

            foreach ($geoPlace->getAdminTypes() as $type) {
                $results = $results->leftJoin("geo_places as {$type}", "{$type}.id", '=', "geo_places.{$type}_id");

                if ($request->input("{$type}_name")) {
                    $results = $results->whereHas($type, static function ($q) use ($request, $type) {
                        $q->where('name', 'like', '%' . $request->input("{$type}_name") . '%');
                    });
                }
            }

            $results = $results->paginate(50);

            $types = $geoPlace->distinct('geo_type')->pluck('geo_type', 'geo_type')->sort();

            return view('imports.places.geo.index', compact('results', 'types', 'request', 'geoSearch'));
        }

        public function show(GeoPlace $geoPlace)
        {
            return view('imports.places.geo.show', compact('geoPlace'));
        }
    }
