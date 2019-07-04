<?php

    namespace App\Http\Requests\Api\Extractors\Wiki;

    use App\Rules\ValidWikiChars;
    use Illuminate\Foundation\Http\FormRequest;

    class InfoboxRequest extends FormRequest
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
                    'bail',
                    'string',
                    'required',
                    new ValidWikiChars,
                ],
                'wanted_keys' => [
                    'string',
                    'nullable',
                ],
                'star_split' => [
                    'sometimes',
                    'boolean',
                    'nullable',
                ],
                'type' => [
                    'in:template,category',
                    'required',
                ],
            ];
        }
    }