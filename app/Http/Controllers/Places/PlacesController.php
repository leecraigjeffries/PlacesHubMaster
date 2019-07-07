<?php

    namespace App\Http\Controllers\Places;

    use App\Http\Requests\Places\StoreRequest;
    use App\Http\Requests\Places\UpdateRequest;
    use App\Models\Place;
    use App\Services\Places\PlaceService;
    use Illuminate\Contracts\View\View;
    use Illuminate\Http\RedirectResponse;

    class PlacesController
    {
        /**
         * @param Place $place
         * @return RedirectResponse|View
         */
        public function show(Place $place)
        {
            if(auth()->user()->hasRole('mod')){
                return redirect()->route('places.edit', $place);
            }

            $next = $place->next();
            $previous = $place->previous();
            $ratio = $place->ratio();
            $siblings = $place->siblings();

            return view('places.show', compact('place', 'next', 'previous', 'ratio', 'siblings'));
        }

        public function edit(Place $place): View
        {
            $edit = true;

            $next = $place->next();
            $previous = $place->previous();
            $ratio = $place->ratio();
            $siblings = $place->siblings();

            return view('places.edit', compact('place', 'next', 'previous', 'ratio', 'siblings', 'edit'));
        }

        /**
         * Update Place.
         *
         * @param Place $place
         * @param PlaceService $placeService
         * @param UpdateRequest $request
         *
         * @return RedirectResponse
         */
        public function update(Place $place, PlaceService $placeService, UpdateRequest $request): RedirectResponse
        {
            $placeService->update($place, $request);

            return back();
        }

        public function create(Place $place, string $type)
        {
            return view('places.create', compact('place', 'type'));
        }

        public function store(StoreRequest $request, Place $place, string $type, PlaceService $placeService): View
        {
            $entries = $placeService->store($place, $type, $request);

            return view('places.store', compact('entries', 'place'));
        }
    }