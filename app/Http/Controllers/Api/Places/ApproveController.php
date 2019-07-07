<?php


    namespace App\Http\Controllers\Api\Places;


    use App\Http\Resources\Api\Places\ApproveResource;
    use App\Models\Place;
    use Carbon\Carbon;

    class ApproveController
    {
        /**
         * @param Place $place
         *
         * @return ApproveResource
         */
        public function approve(Place $place): ApproveResource
        {
            $place->update(['approved_at' => !$place->approved_at ? Carbon::now() : null]);

            return new ApproveResource($place);
        }
    }