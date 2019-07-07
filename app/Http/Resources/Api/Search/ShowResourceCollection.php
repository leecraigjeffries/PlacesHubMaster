<?php

    namespace App\Http\Resources\Api\Search;

    use App\Models\Place;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\ResourceCollection;
    use Illuminate\Support\Collection;

    class ShowResourceCollection extends ResourceCollection
    {
        /**
         * Transform the resource collection into an array.
         *
         * @param Request $request
         * @return Collection
         */
        public function toArray($request): Collection
        {
            foreach ($this->collection as $item) {

                $item->link = route('places.show', $item);

                foreach (Place::typesWithoutLastElement() as $type) {
                    if ($item->$type) {
                        $item->$type->link = route('places.show', $item->$type);
                        unset($item->$type->type, $item->$type->slug, $item->$type->id);
                    }
                }

                $item->parents = [
                    $item->country,
                    $item->macro_region,
                    $item->region,
                    $item->macro_county,
                    $item->county,
                    $item->district,
                    $item->local_admin
                ];

                foreach (Place::typesWithoutLastElement() as $type) {
                    unset($item->$type, $item->{"{$type}_id"});
                }

                $item->parents = array_filter($item->parents);

                $item->type = __("places.{$item->type}");
                unset($item->slug, $item->id);
            }

            return $this->collection;
        }
    }
