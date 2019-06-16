<?php

    namespace App\Http\Controllers\Admin\Imports\Places;

    use App\Http\Controllers\Controller;
    use App\Models\Imports\GeoPlace;
    use App\Services\Imports\Importers\Places\GeoImportService;
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