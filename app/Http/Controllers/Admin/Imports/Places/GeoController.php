<?php

    namespace App\Http\Controllers\Admin\Imports\Places;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Imports\Places\Geo\IndexRequest;
    use App\Models\Imports\GeoPlace;
    use App\Services\Imports\Importers\Places\GeoImportService;
    use App\Services\Imports\Search\Places\GeoSearch;
    use Illuminate\View\View;

    class GeoController extends Controller
    {
        /**
         * @var GeoImportService
         */
        private $geoImportService;

        /**
         * @var GeoPlace
         */
        private $geoPlace;

        public function __construct(GeoImportService $geoImportService, GeoPlace $geoPlace)
        {
            $this->geoImportService = $geoImportService;
            $this->geoPlace = $geoPlace;
        }

        /**
         * @param GeoPlace $geoPlace
         * @param IndexRequest $request
         * @return View
         */
        public function index(GeoPlace $geoPlace, IndexRequest $request): View
        {
            $geoSearch = app(GeoSearch::class, ['inputs' => $request->all()]);

            $results = $geoPlace
                ->orderBy($geoSearch->getOrderBy(), $geoSearch->getOrder())
                ->when($geoSearch->getOrderBy() !== $geoSearch->getDefaultOrderBy(),
                    static function ($query) use ($geoSearch) {
                        $query->orderBy($geoSearch->getDefaultOrderBy(), $geoSearch->getDefaultOrder());
                    })
                ->when($request->input('name') !== null, static function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->input('name') . '%');
                })
                ->when($request->input('geo_code') !== null, static function ($query) use ($request) {
                    $query->where('geo_code', 'like', '%' . $request->input('geo_code') . '%');
                })
                ->when($request->input('geo_code_full') !== null, static function ($query) use ($request) {
                    $query->where('geo_code_full', 'like', '%' . $request->input('geo_code_full') . '%');
                })
                ->when($request->input('geo_type') !== null, static function ($query) use ($request) {
                    $query->where('geo_type', $request->input('geo_type'));
                })
                ->with($geoPlace->getAdminTypes());

            foreach ($geoPlace->getAdminTypes() as $type) {
                if ($request->input("{$type}_name")) {
                    $results = $results->where("{$type}_name", 'like', '%' . $request->input("{$type}_name") . '%');
                }
            }

            $results = $results->paginate(50);

            $types = $geoPlace->distinct('geo_type')->pluck('geo_type', 'geo_type')->sort();

            return view('admin.imports.places.geo.index', compact('results', 'types', 'request', 'geoSearch'));
        }

        /**
         * @param GeoPlace $geoPlace
         * @return View
         */
        public function show(GeoPlace $geoPlace): View
        {
            return view('admin.imports.places.geo.show', compact('geoPlace'));
        }

        /**
         * Confirm import.
         *
         * @return View
         */
        public function create(): View
        {
            $fileExists = file_exists($this->geoImportService->getFilePath());

            $filePath = $this->geoImportService->getFilePath();

            return view('admin.imports.places.geo.create', compact('fileExists', 'filePath'));
        }

        /**
         * Import OS CSV files.
         *
         * @return View
         */
        public function store(): View
        {
            $importSuccess = $this->geoImportService->import(true);

            $rowCount = $this->geoPlace->count();

            return view('admin.imports.places.geo.store', compact('rowCount', 'importSuccess'));
        }

    }