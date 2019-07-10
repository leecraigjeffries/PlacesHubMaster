<?php

    namespace App\Http\Controllers\Places;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Places\DestroyRequest;
    use App\Http\Requests\Places\IndexRequest;
    use App\Http\Requests\Places\StoreRequest;
    use App\Http\Requests\Places\UpdateRequest;
    use App\Models\Place;
    use App\Services\Places\PlaceSearch;
    use App\Services\Places\PlaceService;
    use Exception;
    use Illuminate\Contracts\View\View;
    use Illuminate\Http\RedirectResponse;

    class PlacesController extends Controller
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

        /**
         * @param Place $place
         * @param DestroyRequest $request
         *
         * @return RedirectResponse
         * @throws Exception
         */
        public function destroy(Place $place, DestroyRequest $request): RedirectResponse
        {
            $parent = $place->parent();

            if ($request->input('type') === 'soft') {
                $place->update($request->only(['delete_reason', 'delete_reason_type']));
                $place->delete();
            } else {
                $place->forceDelete();
            }

            return redirect()->route('places.show', $parent);
        }

        /**
         * @param Place $place
         * @param IndexRequest $request
         * @return View
         */
        public function index(Place $place, IndexRequest $request): View
        {
            $placeSearch = app(PlaceSearch::class, ['inputs' => $request->all()]);

            $results = $place->orderBy($placeSearch->getOrderBy(), $placeSearch->getOrder())
                ->when($placeSearch->getOrderBy() !== $placeSearch->getDefaultOrderBy(),
                    static function ($query) use ($placeSearch) {
                        $query->orderBy($placeSearch->getDefaultOrderBy(), $placeSearch->getDefaultOrder());
                    })
                ->when($request->input('name') !== null, static function ($query) use ($request) {
                    $query->where('name', 'like', prepare_name_for_search($request->input('name')));
                })
                ->when($request->input('type') !== null, static function ($query) use ($request) {
                    $query->where('type', $request->input('type'));
                })
                ->with($place::types());

            $results = $results->paginate(50);

            $types = $place->distinct('type')->pluck('type', 'type')->sort();

            return view('places.index', compact('results', 'types', 'request', 'placeSearch'));
        }
    }