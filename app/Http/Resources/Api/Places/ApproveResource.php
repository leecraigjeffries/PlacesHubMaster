<?php

    namespace App\Http\Resources\Api\Places;

    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;

    class ApproveResource extends JsonResource
    {
        /**
         * Transform the resource into an array.
         *
         * @param Request $request
         * @return array
         */
        public function toArray($request): array
        {
            return [
                'approved' => (bool)$this->approved_at
            ];
        }
    }