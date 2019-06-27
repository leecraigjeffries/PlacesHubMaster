<?php


    namespace App\Http\Requests\Imports\Places\Osm;

    use App\Models\Imports\OsmPlace;
    use App\Rules\Order;
    use App\Services\Imports\Importers\Places\OsmImportService;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Validation\Rule;

    class IndexRequest extends FormRequest
    {
        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize(): bool
        {
            return true;
        }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules(): array
        {
            $service = app(OsmImportService::class);

            $rules = [
                'name' => [
                    'sometimes',
                    'nullable',
                    'string',
                    'max:191'
                ],
                'osm_type' => [
                    Rule::in(Cache::remember('osm_search_types', 30, static function () {
                        return OsmPlace::select('osm_type')->distinct()->pluck('osm_type');
                    })),
                    'nullable'
                ],
                'order_by' => [
                    Rule::in([
                        'name',
                        'osm_type',
                        'state_name',
                        'county_name',
                        'city_name'
                    ])
                ],
                'order' => [
                    new Order
                ]
            ];

            foreach ($service->getAdminTypes() as $type) {
                $rules["{$type}_name"] = [
                    'sometimes',
                    'nullable',
                    'string',
                    'max:191'
                ];
            }

            return $rules;

        }
    }