<?php

    namespace App\Rules;

    use App\Contracts\Extractors\ExtractorContract;
    use App\Services\Extractors\WikiExtractor;
    use Illuminate\Contracts\Validation\Rule;

    class ValidWikiTitle implements Rule
    {
        /**
         * Determine if the validation rule passes.
         *
         * @param string $attribute
         * @param mixed $value
         * @return bool
         */
        public function passes($attribute, $value): bool
        {
            $info = app(WikiExtractor::class)->getInfo($value);

            return !$info['wiki_missing'];
        }

        /**
         * Get the validation error message.
         *
         * @return string
         */
        public function message(): string
        {
            return ':attribute is not a valid Wikipedia title';
        }
    }
