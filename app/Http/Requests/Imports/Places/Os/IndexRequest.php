<?php


    namespace App\Http\Requests\Imports\Places\Os;

    use App\Models\Imports\OsPlace;
    use App\Rules\Order;
    use App\Services\Imports\Importers\Places\OsImportService;
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
            $service = app(OsImportService::class);

            $rules = [
                'name' => [
                    'sometimes',
                    'nullable',
                    'string',
                    'max:191'
                ],
                'os_type' => [
                    Rule::in(Cache::remember('os_search_types', 30, static function () {
                        return OsPlace::select('os_type')->distinct()->pluck('os_type');
                    })),
                    'nullable'
                ],
                'order_by' => [
                    Rule::in([
                        'name',
                        'os_type',
                        'macro_region_name',
                        'region_name',
                        'county_name',
                        'district_name',
                        'county_type',
                        'district_type'
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

            foreach (['county', 'district'] as $type) {
                $rules["{$type}_type"] = [
                    'sometimes',
                    'nullable',
                    'string',
                    'max:191'
                ];
            }

            return $rules;

        }
    }