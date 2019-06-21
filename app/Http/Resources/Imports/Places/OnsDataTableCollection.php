<?php

namespace App\Http\Resources\Imports\Places;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OnsDataTableCollection extends ResourceCollection
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
            $item->link = route('imports.places.ons.show', ['onsPlace' => $item]);
        }

        return [
            'data' => $this->collection,
        ];
    }
}
