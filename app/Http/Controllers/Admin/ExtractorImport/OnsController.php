<?php

    namespace App\Http\Controllers\Admin\ExtractorImport;

    use App\Http\Controllers\Controller;
    use App\Services\ExtractorImport\OnsExtractorImport;
    use Illuminate\Contracts\View\View;

    class OnsController extends Controller
    {
        public function edit(): View
        {
            return view('admin.extractor-import.ons.edit');
        }

        public function update(OnsExtractorImport $onsExtractorImport): View
        {
            $success = $onsExtractorImport->updateBoundaries();

            return view('admin.extractor-import.ons.update', compact('success'));
        }
    }
