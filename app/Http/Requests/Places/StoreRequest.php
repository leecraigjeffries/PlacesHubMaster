<?php

    namespace App\Http\Requests\Places;

    use App\Rules\ValidWikiChars;
    use App\Rules\ValidWikiTitle;
    use Illuminate\Foundation\Http\FormRequest;

    class StoreRequest extends FormRequest
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
                'title' => [
                    'array',
                ],
                'name' => [
                    'array',
                    'required'
                ],
                'name.*' => [
                    'max:191',
                    'string',
                    'distinct'
                ],
                'title.*' => [
                    'bail',
                    'max:191',
                    'string',
                    'distinct',
                    'nullable',
                    new ValidWikiTitle,
                    new ValidWikiChars,
                ]
            ];
        }
    }
