<?php

    namespace App\Http\Requests\Places\MoveChildren;

    use App\Models\Place;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Validation\Rule;

    class UpdateRequest extends FormRequest
    {
        /**
         * @param null $keys
         * @return array
         */
        public function all($keys = null): array
        {
            $data = parent::all($keys);
            $data['destination'] = $this->route('destination');
            return $data;
        }

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
            return [
                'type' => [
                    Rule::in(array_merge(['all'], Place::types()))
                ]
            ];
        }
    }
