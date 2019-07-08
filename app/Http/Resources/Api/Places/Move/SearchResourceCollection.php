<?php

    namespace App\Http\Resources\Api\Places\Move;

    use App\Models\Place;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\ResourceCollection;
    use Illuminate\Support\Collection;

    class SearchResourceCollection extends ResourceCollection
    {
        /**
         * @var Place
         */
        protected $currentParent;

        /**
         * @var string
         */
        protected $type;

        public function __construct($resource, Place $currentParent, string $type)
        {
            parent::__construct($resource);

            $this->currentParent = $currentParent;
            $this->type = $type;
        }

        /**
         * Transform the resource collection into an array.
         *
         * @param Request $request
         * @return Collection
         */
        public function toArray($request): Collection
        {

            foreach ($this->collection as $item) {

                $item->uri = route('places.move-children.update', [$this->currentParent, $item, $this->type]);

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
                    if ($item->$type) {
                        $item->$type->uri = route('places.show', $item->$type);
                    }

                    unset($item->$type, $item->{"{$type}_id"});
                }

                $item->parents = array_filter($item->parents);

                $item->type = __("placeshub.{$item->type}");
            }

            return $this->collection;
        }
    }
