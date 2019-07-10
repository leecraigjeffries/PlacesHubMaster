<?php

    namespace App\Http\Controllers\Admin\Dumps;

    use App\Exports\PlacesExport;
    use App\Http\Controllers\Controller;
    use Illuminate\Contracts\View\View;
    use Maatwebsite\Excel\Facades\Excel;

    class PlacesController extends Controller
    {
        public function create(): View
        {
            return view('admin.dumps.places.create');
        }

        public function store()
        {
            Excel::store(new PlacesExport,
                'places.csv',
                'local',
                \Maatwebsite\Excel\Excel::CSV);

            return 'done';
        }
    }