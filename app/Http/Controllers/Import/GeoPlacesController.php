<?php

    namespace App\Http\Controllers\Import;

    use App\Http\Controllers\Controller;
    use App\Http\Controllers\Import\Places\Geo\IndexRequest;
    use App\Models\Import\GeoPlace;

    class GeoPlacesController extends Controller
    {
        public function index(GeoPlace $geoPlace, IndexRequest $request)
        {
            $results = $geoPlace
                ->select(['geo_places.*'])

                ->when($request->input('order_by') !== null, static function($query) use ($request){
                    $query->orderBy($request->input('order_by'), $request->input('order') ?: 'asc');
                })
                ->when($request->input('name') !== null, static function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->input('name') . '%');
                })->when($request->input('type') !== null, static function ($query) use ($request) {
                    $query->where('geo_places.type', $request->input('type'));
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

            $types = $geoPlace->distinct('type')->pluck('type', 'type')->sort();

            return view('import.places.geo.index', compact('results', 'types', 'request'));
        }
    }
