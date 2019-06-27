<?php

    namespace App\Http\Controllers\Admin\Imports\Places;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Imports\Places\Osm\IndexRequest;
    use App\Models\Imports\OsmPlace;
    use App\Services\Imports\Importers\Places\OsmImportService;
    use App\Services\Imports\Search\Places\OsmSearch;
    use Illuminate\View\View;

    class OsmController extends Controller
    {
        /**
         * @var OsmPlace
         */
        private $osmPlace;

        public function __construct(OsmImportService $osmImportService, OsmPlace $osmPlace)
        {
            $this->osmImportService = $osmImportService;
            $this->osmPlace = $osmPlace;
        }

        /**
         * @param OsmPlace $osmPlace
         * @param IndexRequest $request
         * @return View
         */
        public function index(OsmPlace $osmPlace, IndexRequest $request): View
        {
            $osmSearch = app(OsmSearch::class, ['inputs' => $request->all()]);

            $results = $osmPlace
                ->orderBy($osmSearch->getOrderBy(), $osmSearch->getOrder())
                ->when($osmSearch->getOrderBy() !== $osmSearch->getDefaultOrderBy(),
                    static function ($query) use ($osmSearch) {
                        $query->orderBy($osmSearch->getDefaultOrderBy(), $osmSearch->getDefaultOrder());
                    })
                ->when($request->input('name') !== null, static function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->input('name') . '%');
                })
                ->when($request->input('osm_type') !== null, static function ($query) use ($request) {
                    $query->where('osm_type', $request->input('osm_type'));
                });

            foreach ($osmPlace->getAdminTypes() as $type) {
                if ($request->input("{$type}_name")) {
                    $results = $results->where("{$type}_name", 'like', '%' . $request->input("{$type}_name") . '%');
                }
            }

            $results = $results->paginate(50);

            $types = $osmPlace->distinct()->pluck('osm_type', 'osm_type')->sort();

            return view('admin.imports.places.osm.index', compact('results', 'types', 'request', 'osmSearch'));
        }

        /**
         * @param OsmPlace $osmPlace
         * @return View
         */
        public function show(OsmPlace $osmPlace): View
        {
            return view('admin.imports.places.osm.show', compact('osmPlace'));
        }

        /**
         * Confirm import.
         *
         * @return View
         */
        public function create(): View
        {
            $fileExists = file_exists($this->osmImportService->getFilePath());

            $filePath = $this->osmImportService->getFilePath();

            return view('admin.imports.places.osm.create', compact('fileExists', 'filePath'));
        }

        /**
         * Import OS CSV files.
         *
         * @return View
         */
        public function store(): View
        {
            $importSuccess = $this->osmImportService->import(true);

            $rowCount = $this->osmPlace->count();

            return view('admin.imports.places.osm.store', compact('rowCount', 'importSuccess'));
        }

    }