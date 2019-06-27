<?php

    namespace App\Http\Controllers\Places;

    use App\Models\Place;
    use Illuminate\Contracts\View\View;

    class PlacesController
    {
        public function show(Place $place): View
        {
            return view('places.show', compact('place'));
        }
    }