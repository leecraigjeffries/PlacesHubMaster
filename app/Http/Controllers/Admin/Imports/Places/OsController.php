<?php

    namespace App\Http\Controllers\Admin\Imports\Places;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Imports\Places\Os\IndexRequest;
    use App\Models\Imports\OsPlace;
    use App\Services\Imports\Importers\Places\OsImportService;
    use App\Services\Imports\Search\Places\OsSearch;
    use Illuminate\View\View;

    class OsController extends Controller
    {
        /**
         * @var OsImportService
         */
        private $osImportService;

        /**
         * @var OsPlace
         */
        private $osPlace;

        public function __construct(OsImportService $osImportService, OsPlace $osPlace)
        {
            $this->osImportService = $osImportService;
            $this->osPlace = $osPlace;
        }

        /**
         * @param OsPlace $osPlace
         * @param IndexRequest $request
         * @return View
         */
        public function index(OsPlace $osPlace, IndexRequest $request): View
        {
            $osSearch = app(OsSearch::class, ['inputs' => $request->all()]);

            $results = $osPlace
                ->orderBy($osSearch->getOrderBy(), $osSearch->getOrder())
                ->with($osPlace->getAdminTypes())
                ->when($osSearch->getOrderBy() !== $osSearch->getDefaultOrderBy(),
                    static function ($query) use ($osSearch) {
                        $query->orderBy($osSearch->getDefaultOrderBy(), $osSearch->getDefaultOrder());
                    })
                ->when($request->input('name') !== null, static function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->input('name') . '%');
                })
                ->when($request->input('os_type') !== null, static function ($query) use ($request) {
                    $query->where('os_type', $request->input('os_type'));
                });

            foreach ($osPlace->getAdminTypes() as $type) {
                if ($request->input("{$type}_name")) {
                    $results = $results->where("{$type}_name", 'like', '%' . $request->input("{$type}_name") . '%');
                }
            }

            $results = $results->paginate(50);

            $types = $osPlace->distinct()->pluck('os_type', 'os_type')->sort();

            return view('admin.imports.places.os.index', compact('results', 'types', 'request', 'osSearch'));
        }

        /**
         * @param OsPlace $osPlace
         * @return View
         */
        public function show(OsPlace $osPlace): View
        {
            return view('admin.imports.places.os.show', compact('osPlace'));
        }

        /**
         * Confirm import.
         *
         * @return View
         */
        public function create(): View
        {
            $fileExists = file_exists($this->osImportService->getFilePath());

            $filePath = $this->osImportService->getFilePath();

            return view('admin.imports.places.os.create', compact('fileExists', 'filePath'));
        }

        /**
         * Import OS CSV files.
         *
         * @return View
         */
        public function store(): View
        {
            $importSuccess = $this->osImportService->import(true);

            $rowCount = $this->osPlace->count();

            return view('admin.imports.places.os.store', compact('rowCount', 'importSuccess'));
        }

    }