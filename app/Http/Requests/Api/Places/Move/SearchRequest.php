<?php

namespace App\Http\Requests\Api\Places\Move;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
//            'q' => [
//                'string',
//                'max:191',
//                // TODO Delete this
//                'nullable',
//                'sometimes'
//            ]
        ];
    }
}
