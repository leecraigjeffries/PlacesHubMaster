<?php

    namespace App\Http\Controllers\Admin\ExtractorImport;

    use App\Http\Controllers\Controller;
    use App\Services\ExtractorImport\OsniExtractorImport;
    use Exception;
    use Illuminate\Contracts\View\View;

    class OsniController extends Controller
    {
        /**
         * @return View
         */
        public function edit(): View
        {
            return view('admin.extractor-import.osni.edit');
        }

        /**
         * @param OsniExtractorImport $osniExtractorImport
         * @return View
         * @throws Exception
         */
        public function update(OsniExtractorImport $osniExtractorImport): View
        {
            $success = $osniExtractorImport->import();

            return view('admin.extractor-import.osni.update', compact('success'));
        }
    }