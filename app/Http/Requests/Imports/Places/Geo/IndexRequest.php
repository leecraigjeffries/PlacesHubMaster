<?php


    namespace App\Http\Controllers\Import\Places\Geo;

    use App\Rules\Order;
    use App\Services\Imports\Importers\Places\GeoImportService;
    use App\Services\Imports\Search\Places\GeoSearch;
    use Illuminate\Foundation\Http\FormRequest;
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
            $service = app(GeoImportService::class);

            $rules = [
                'name' => [
                    'sometimes',
                    'nullable',
                    'string',
                    'max:191'
                ],
                'geo_code' => [
                    'sometimes',
                    'nullable',
                    'string',
                    'max:191'
                ],
                'geo_code_full' => [
                    'sometimes',
                    'nullable',
                    'string',
                    'max:191'
                ],
                'geo_type' => [
                    Rule::in($service->getValidTypes()),
                    'nullable'
                ],
                'order_by' => [
                    Rule::in([
                        'geo_places.name',
                        'geo_places.type',
                        'geo_places.geo_code',
                        'geo_places.geo_code_full',
                        'adm1.name',
                        'adm2.name',
                        'adm3.name',
                        'adm4.name'
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