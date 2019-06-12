<?php

    namespace App\Http\Controllers\Admin\Import;

    use App\Http\Controllers\Controller;
    use App\Models\Import\GeoPlace;
    use App\Services\Import\Importers\Places\GeoImportService;
    use Illuminate\View\View;

    class GeoPlacesController extends Controller
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
         * Confirm import.
         *
         * @return View
         */
        public function create(): View
        {
            $fileExists = file_exists($this->geoImportService->getFilePath());

            $filePath = $this->geoImportService->getFilePath();

            return view('admin.import.geo.places.create', compact('fileExists', 'filePath'));
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

            return view('admin.import.geo.places.store', compact('rowCount', 'importSuccess'));
        }

    }