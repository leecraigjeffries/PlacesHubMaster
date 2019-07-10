<?php


    namespace App\Http\Requests\Places;

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
            $rules = [
                'name' => [
                    'sometimes',
                    'nullable',
                    'string',
                    'max:191'
                ],
                'order_by' => [
                    Rule::in([
                        'name'
                    ])
                ],
                'order' => [
                    new Order
                ]
            ];

            return $rules;
        }
    }