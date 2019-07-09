<?php

    namespace App\Http\Requests\Places;

    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Validation\Rule;

    class DestroyRequest extends FormRequest
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
            return [
                'type' => [
                    'in:soft,force',
                    'string',
                    'required'
                ],
                'delete_reason_type' => [
                    Rule::in(array_keys(__('placeshub.delete_reasons'))),
                    'string',
                    'required'
                ],
                'notes' => [
                    'string',
                    'present',
                    'nullable',
                    'max:191'
                ]
            ];
        }
    }
