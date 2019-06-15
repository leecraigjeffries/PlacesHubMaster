<?php

    namespace App\Http\Controllers\Import;

    use App\Http\Controllers\Controller;
    use App\Http\Controllers\Import\Places\Geo\IndexRequest;
    use App\Models\Import\GeoPlace;
    use App\Services\Import\Search\Places\GeoSearch;
    use Illuminate\View\View;

    class GeoPlacesController extends Controller
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

            return view('import.places.geo.index', compact('results', 'types', 'request', 'geoSearch'));
        }
    }
