<?php

    namespace App\Http\Controllers\Admin\Imports\LocalAdmins;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Imports\LocalAdmins\Ons\IndexRequest;
    use App\Models\Imports\OnsLocalAdmin;
    use App\Models\Place;
    use App\Services\Imports\Importers\LocalAdmins\OnsImportService;
    use App\Services\Imports\Search\LocalAdmins\OnsSearch;
    use Illuminate\View\View;

    class OnsController extends Controller
    {
        /**
         * @var OnsImportService
         */
        private $onsImportService;

        /**
         * @var OnsLocalAdmin
         */
        private $onsLocalAdmin;

        public function __construct(OnsImportService $onsImportService, OnsLocalAdmin $onsLocalAdmin)
        {
            $this->onsImportService = $onsImportService;
            $this->onsLocalAdmin = $onsLocalAdmin;
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

            return view('admin.imports.local-admins.ons.create', compact('fileExists', 'filePath'));
        }

        /**
         * Import OS CSV files.
         *
         * @return View
         */
        public function store(): View
        {
            $importSuccess = $this->onsImportService->import(true);

            $rowCount = $this->onsLocalAdmin->count();

            return view('admin.imports.local-admins.ons.store', compact('rowCount', 'importSuccess'));
        }

        /**
         * @param Place $place
         * @return View
         */
        public function compare(Place $place): View
        {
            app('debugbar')->disable();

            $onsAll = $this->onsLocalAdmin
                ->pluck('id');

            $placeExtra = $place->whereNotIn('ons_id', $onsAll)
                ->whereType('local_admin')
                ->orderBy('ons_id')
                ->get();

            $placeAll = $place->whereNotNull('ons_id')
                ->whereType('local_admin')
                ->pluck('ons_id');

            $onsExtra = $this->onsLocalAdmin
                ->whereNotIn('id', $placeAll)
                ->orderBy('id')
                ->get();

            return view('admin.imports.local-admins.ons.compare', compact('onsExtra', 'placeExtra'));
        }

    }