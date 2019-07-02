<?php

    namespace App\Http\Controllers\Places;

    use App\Http\Requests\Places\PlaceStoreRequest;
    use App\Models\Place;
    use Illuminate\Contracts\View\View;

    class PlacesController
    {
        public function show(Place $place): View
        {
            $next = $place->getNext();
            $previous = $place->getPrevious();
            $ratio = $place->getRatio();
            $siblings = $place->getSiblings();

            return view('places.show', compact('place', 'next', 'previous', 'ratio', 'siblings'));
        }

        public function edit(Place $place): View
        {
            $edit = true;

            $next = $place->getNext();
            $previous = $place->getPrevious();
            $ratio = $place->getRatio();
            $siblings = $place->getSiblings();

            return view('places.edit', compact('place', 'next', 'previous', 'ratio', 'siblings', 'edit'));
        }

        public function create(Place $place, string $type)
        {
            return view('places.create', compact('place', 'type'));
        }

        public function store(PlaceStoreRequest $request, Place $place, $type)
        {
            $entries = $this->service->store($place, $type, $request);

            return view('places.store', compact('entries', 'place'));
        }
    }