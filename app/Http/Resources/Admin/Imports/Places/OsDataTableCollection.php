<?php

namespace App\Http\Resources\Admin\Imports\Places;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OsDataTableCollection extends ResourceCollection
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
            $item->link = route('admin.imports.places.os.show', ['osPlace' => $item]);
        }

        return [
            'data' => $this->collection,
        ];
    }
}
