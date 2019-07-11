<?php

    namespace App\Http\Controllers\Admin\Imports\Places;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Imports\Places\Ons\IndexRequest;
    use App\Models\Imports\OnsPlace;
    use App\Services\Imports\Importers\Places\OnsImportService;
    use App\Services\Imports\Search\Places\OnsSearch;
    use Illuminate\Contracts\View\View;

    class OnsController extends Controller
    {
        /**
         * @var OnsImportService
         */
        private $onsImportService;

        /**
         * @var OnsPlace
         */
        private $onsPlace;

        public function __construct(OnsImportService $onsImportService, OnsPlace $onsPlace)
        {
            $this->onsImportService = $onsImportService;
            $this->onsPlace = $onsPlace;
        }

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

            return view('admin.imports.places.ons.index', compact('results', 'types', 'request', 'onsSearch'));
        }

        /**
         * @param OnsPlace $onsPlace
         * @return View
         */
        public function show(OnsPlace $onsPlace): View
        {
            $otherIpnIds = [];
            if($onsPlace->ons_id !== null) {
                $otherIpnIds = $onsPlace->whereType($onsPlace->type)
                    ->where('ons_id', $onsPlace->ons_id)
                    ->where('id', '!=', $onsPlace->id)
                    ->get();
            }

            $otherNames = [];
            if($onsPlace->ipn_id !== null) {
                $otherNames = $onsPlace->whereType($onsPlace->type)
                    ->where('ipn_id', $onsPlace->ipn_id)
                    ->where('id', '!=', $onsPlace->id)
                    ->get();
            }

            return view('admin.imports.places.ons.show', compact('onsPlace', 'otherIpnIds', 'otherNames'));
        }

        /**
         * @param string $ipnId
         * @param OnsPlace $onsPlace
         * @return View
         */
        public function showIpnId(string $ipnId, OnsPlace $onsPlace): View
        {
            $place = $onsPlace->where('ipn_id', $ipnId)->firstOrFail();

            return $this->show($place);
        }

        /**
         * @param string $onsId
         * @param OnsPlace $onsPlace
         * @return View
         */
        public function showOnsId(string $onsId, OnsPlace $onsPlace): View
        {
            $place = $onsPlace->where('ons_id', $onsId)->firstOrFail();

            return $this->show($place);
        }

        /**
         * Confirm import.
         *
         * @return View
         */
        public function create(): View
        {
            $fileExists = file_exists($this->onsImportService->getFilePath());

            $filePath = $this->onsImportService->getFilePath();

            return view('admin.imports.places.ons.create', compact('fileExists', 'filePath'));
        }

        /**
         * Import OS CSV files.
         *
         * @return View
         */
        public function store(): View
        {
            $importSuccess = $this->onsImportService->import(true);

            $rowCount = $this->onsPlace->count();

            return view('admin.imports.places.ons.store', compact('rowCount', 'importSuccess'));
        }

        public function compare(Place $place):View
        {
            app('debugbar')->disable();

            $onsAll = $this->onsPlace
                ->whereNotNull('ons_id')
                ->pluck('ons_id');

            $placeExtra = $place->whereNotIn('ons_id', $onsAll)
                ->orderBy('ons_id')
                ->get();

            $placeAll = $place->whereNotNull('ons_id')
                ->pluck('ons_id');

            $onsExtra = $this->onsPlace
                ->whereNotIn('ons_id', $placeAll)
                ->orderBy('ons_id')
                ->get();

            return view('admin.imports.places.ons.compare', compact('onsExtra', 'placeExtra'));
        }

    }