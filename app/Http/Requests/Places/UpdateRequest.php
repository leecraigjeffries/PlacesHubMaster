<?php

    namespace App\Http\Requests\Places;

    use App\Rules\ValidWikiChars;
    use App\Rules\ValidWikiTitle;
    use Illuminate\Foundation\Http\FormRequest;

    class UpdateRequest extends FormRequest
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
                    'max:191',
                    'string',
                    'required'
                ],
                'official_name' => [
                    'max:191',
                    'string',
                    'nullable'
                ],
                'wiki_title' => [
                    'max:191',
                    'string',
                    'nullable',
                    new ValidWikiTitle,
                    new ValidWikiChars
                ],
                'wikidata_id' => [
                    'regex:/Q[0-9]+/',
                    'string',
                    'nullable'
                ],
                'osm_id' => [
                    'integer',
                    'nullable',
                    'required_with:osm_network_type'
                ],
                'osm_network_type' => [
                    'nullable',
                    'string',
                    'in:node,relation,way',
                    'required_with:osm_id'
                ],
                'os_id' => [
                    'nullable',
                    'string',
                ],
                'ons_id' => [
                    'string',
                    'nullable',
                    'regex:/^(E|W|S)\d{8}$/'
                ],
                'ipn_id' => [
                    'nullable',
                    'string',
                    'regex:/^(IPN)\d{7}$/'
                ],
                'geo_id' => [
                    'integer',
                    'nullable'
                ],
                'lat' => [
                    'max:90',
                    'min:-90',
                    'numeric',
                    'nullable',
                    'present',
                    'required_with:lon'
                ],
                'lon' => [
                    'max:180',
                    'min:-180',
                    'numeric',
                    'nullable',
                    'present',
                    'required_with:lat'
                ]
            ];

            for ($i = 2; $i <= 4; $i++) {
                $rules["geo_id_{$i}"] = $rules['geo_id'];
            }

            foreach ([
                         'wiki_title',
                         'wikidata_id',
                         'osm_id',
                         'osm_network_type',
                         'os_id',
                         'ons_id',
                         'ipn_id',
                         'geo_id',
                         'geo_id_2',
                         'geo_id_3',
                         'geo_id_4',
                     ] as $col) {
                $rules["{$col}_null"] = ['boolean', 'sometimes'];
            }

            return $rules;
        }
    }
