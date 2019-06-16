<?php

    namespace App\Http\Controllers\Admin\Imports\Places;

    use App\Http\Controllers\Controller;
    use App\Models\Imports\OnsPlace;
    use App\Services\Imports\Importers\Places\OnsImportService;
    use Illuminate\View\View;

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

    }