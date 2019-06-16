<?php

namespace App\Http\Resources\Imports\Places;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GeoDataTableCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        foreach ($this->collection as $item) {
            $item->link = route('imports.places.geo.show', ['geoPlace' => $item]);
        }

        return [
            'data' => $this->collection,
        ];
    }
}
