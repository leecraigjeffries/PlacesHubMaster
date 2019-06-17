<?php


    namespace App\Http\Requests\Imports\Places\Geo;

    use App\Rules\Order;
    use App\Services\Imports\Importers\Places\GeoImportService;
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
                        'name',
                        'geo_type',
                        'geo_code',
                        'geo_code_full',
                        'adm1_name',
                        'adm2_name',
                        'adm3_name',
                        'adm4_name'
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