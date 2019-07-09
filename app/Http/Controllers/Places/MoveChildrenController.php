<?php

    namespace App\Http\Controllers\Places;

    use App\Http\Requests\Places\MoveChildren\SelectParentRequest;
    use App\Models\Place;
    use Arr;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;

    class MoveChildrenController extends Controller
    {
        public function selectType(Place $place)
        {
            $counts = $place->childCount();

            if (array_sum($counts) === 0) {
                return back()->withErrors(['msg' => 'Place contains no children']);
            }

            return view('places.move-children.select-type', compact('place', 'counts'));
        }

        public function selectParent(SelectParentRequest $request, Place $place)
        {
            $type = $request->input('type');

            return view('places.move-children.select-parent', compact('place', 'request', 'type'));
        }

        public function update(Place $place, Place $destination, string $type)
        {
            $destination_data = Arr::only($destination->toArray(), $place->seniorColumns(true));

            $destination_data[$destination->type . '_id'] = $destination->id;

            $query = $place->where($place->type . '_id', $place->id);

            if ($type !== 'all') {
                // TODO does this need to be whereIn with junior columns of type with type included?
                $query = $query->whereType($type);
            }

            $query->update($destination_data);

            return redirect()->route('places.show', $place);
        }
    }
