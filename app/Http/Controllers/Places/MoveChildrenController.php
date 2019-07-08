<?php

    namespace App\Http\Controllers\Places;

    use App\Http\Requests\Places\MoveChildren\SelectParentRequest;
    use App\Models\Place;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;

    class MoveChildrenController extends Controller
    {
        public function selectType(Place $place)
        {
            $counts = [];
            foreach ($place->juniorTypes() as $juniorType) {
                $query = $place->where($place->type_column, $place->id)
                    ->whereType($juniorType);

                foreach ($place::sliceTypes($place->type, $juniorType) as $middleType) {
                    $query = $query->whereNull("{$middleType}_id");
                }

                $counts[$juniorType] = $query->count();
            }

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
    }
