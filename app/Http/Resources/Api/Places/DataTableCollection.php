<?php

    namespace App\Http\Resources\Api\Places;

    use Illuminate\Http\Resources\Json\ResourceCollection;

    class DataTableCollection extends ResourceCollection
    {
        /**
         * Transform the resource collection into an array.
         *
         * @param $request
         * @return array
         */
        public function toArray($request): array
        {
            foreach ($this->collection as $item) {
                $item->link = route('places.show', ['place' => $item]);
            }

            return [
                'data' => $this->collection,
            ];
        }
    }
