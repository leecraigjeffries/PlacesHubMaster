<?php

namespace App\Exports;

use App\Models\Place;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class PlacesExport implements FromCollection
{
    use Exportable;

    /**
    * @return Collection
    */
    public function collection()
    {
        return Place::all();
    }
}
