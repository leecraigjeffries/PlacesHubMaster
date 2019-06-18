<?php

    namespace App\Http\Controllers\Admin\Imports\Places;

    use App\Http\Controllers\Controller;
    use App\Models\Imports\OsPlace;
    use App\Services\Imports\Importers\Places\OsImportService;
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