<?php


    namespace App\Http\Requests\Imports\Places\Ons;

    use App\Rules\Order;
    use App\Services\Imports\Importers\Places\OnsImportService;
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
            $service = app(OnsImportService::class);

            $rules = [
                'name' => [
                    'sometimes',
                    'nullable',
                    'string',
                    'max:191'
                ],
                'ipn_id' => [
                    'max:10',
                    'string',
                    'sometimes',
                    'nullable',
                    'regex:/^(IPN)?[0-9]*$/'
                ],
                'ons_id' => [
                    'max:9',
                    'string',
                    'sometimes',
                    'nullable',
                    'regex:/^(E|W|S)?[0-9]*$/'
                ],
                'ons_type' => [
                    Rule::in($service->getValidTypes()),
                    'nullable'
                ],
                'order_by' => [
                    Rule::in([
                        'name',
                        'ons_type',
                        'ipn_id',
                        'ons_id',
                        'county_name',
                        'district_name'
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