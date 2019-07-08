<?php

    namespace App\Http\Controllers\Api\Places;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Api\Places\Move\SearchRequest;
    use App\Http\Resources\Api\Places\Move\SearchResourceCollection;
    use App\Models\Place;

    class MoveChildrenController extends Controller
    {
        public function search(Place $place, string $type, SearchRequest $request): SearchResourceCollection
        {
            if ($type === 'all') {
                foreach ($place->juniorTypes() as $juniorType) {
                    $query = $place->where($place->type_column, $place->id)
                        ->whereType($juniorType);

                    foreach ($place::sliceTypes($place->type, $juniorType) as $middleType) {
                        $query = $query->whereNull("{$middleType}_id");
                    }

                    if (count($query->limit(1)->get())) {
                        $parentTypes = array_intersect(
                            $place->parentTypes($juniorType),
                            $parentTypes ?? $place::types()
                        );
                    }
                }
            }

            $results = $place->whereIn('type', $parentTypes ?? $place->parentTypes($type))
                ->where('name', 'like', prepare_name_for_search($request->input('q')))
                ->where('id', '<>', $place->id)
                ->orderBy('name')
                ->limit(10);

            foreach ($place::typesWithoutLastElement() as $parent) {
                $results = $results->with(["{$parent}:name,slug,id"]);
            }

            $results = $results->get(array_merge(['name', 'slug', 'id', 'type'],
                $place::typesWithoutLastElement(true)));

            return new SearchResourceCollection($results, $place, $type);
        }
    }
