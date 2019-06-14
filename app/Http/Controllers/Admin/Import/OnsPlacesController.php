<?php

    namespace App\Http\Controllers\Admin\Import;

    use App\Http\Controllers\Controller;
    use App\Models\Import\OnsPlace;
    use App\Services\Import\Importers\Places\OnsImportService;
    use Illuminate\View\View;

    class OnsPlacesController extends Controller
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

            return view('admin.import.ons.places.create', compact('fileExists', 'filePath'));
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

            return view('admin.import.ons.places.store', compact('rowCount', 'importSuccess'));
        }

    }