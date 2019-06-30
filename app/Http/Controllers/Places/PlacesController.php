<?php

    namespace App\Http\Controllers\Places;

    use App\Models\Place;
    use Illuminate\Contracts\View\View;

    class PlacesController
    {
        public function show(Place $place): View
        {
            $next = $place->next();
            $previous = $place->previous();
            $ratio = $place->getRatio();
            $siblings = $place->siblings();

            return view('places.show', compact('place', 'next', 'previous', 'ratio', 'siblings'));
        }
    }