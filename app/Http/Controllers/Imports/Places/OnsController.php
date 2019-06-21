<?php

    namespace App\Http\Controllers\Imports\Places;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Imports\Places\Ons\IndexRequest;
    use App\Models\Imports\OnsPlace;
    use App\Services\Imports\Search\Places\OnsSearch;
    use Illuminate\View\View;

    class OnsController extends Controller
    {
        /**
         * @param OnsPlace $onsPlace
         * @param IndexRequest $request
         * @return View
         */
        public function index(OnsPlace $onsPlace, IndexRequest $request): View
        {
            $onsSearch = app(OnsSearch::class, ['inputs' => $request->all()]);

            $results = $onsPlace
                ->orderBy($onsSearch->getOrderBy(), $onsSearch->getOrder())
                ->with($onsPlace->getAdminTypes())
                ->when($onsSearch->getOrderBy() !== $onsSearch->getDefaultOrderBy(),
                    static function ($query) use ($onsSearch) {
                        $query->orderBy($onsSearch->getDefaultOrderBy(), $onsSearch->getDefaultOrder());
                    })
                ->when($request->input('name') !== null, static function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->input('name') . '%');
                })
                ->when($request->input('ipn_id') !== null, static function ($query) use ($request) {
                    $query->where('ipn_id', 'like', '%' . $request->input('ipn_id') . '%');
                })
                ->when($request->input('ons_id') !== null, static function ($query) use ($request) {
                    $query->where('ons_id', 'like', '%' . $request->input('ons_id') . '%');
                })
                ->when($request->input('ons_type') !== null, static function ($query) use ($request) {
                    $query->where('ons_type', $request->input('ons_type'));
                });

            foreach ($onsPlace->getAdminTypes() as $type) {
                if ($request->input("{$type}_name")) {
                    $results = $results->where("{$type}_name", 'like', '%' . $request->input("{$type}_name") . '%');
                }
            }

            $results = $results->paginate(50);

            $types = $onsPlace->distinct('ons_type')->pluck('ons_type', 'ons_type')->sort();

            return view('imports.places.ons.index', compact('results', 'types', 'request', 'onsSearch'));
        }

        /**
         * @param OnsPlace $onsPlace
         * @return View
         */
        public function show(OnsPlace $onsPlace): View
        {
            $otherIpnIds = $onsPlace->whereType($onsPlace->type)
                ->where('ons_id', $onsPlace->ons_id)
                ->where('id', '!=', $onsPlace->id)
                ->get();

            $otherNames = $onsPlace->whereType($onsPlace->type)
                ->where('ipn_id', $onsPlace->ipn_id)
                ->where('id', '!=', $onsPlace->id)
                ->get();

            return view('imports.places.ons.show', compact('onsPlace', 'otherIpnIds', 'otherNames'));
        }

        public function showIpnId(string $ipnId, OnsPlace $onsPlace)
        {
            $place = $onsPlace->where('ipn_id', $ipnId)->firstOrFail();

            return $this->show($place);
        }
    }
