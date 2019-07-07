<?php

    namespace App\Rules;

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
            return false;
            $info = app(WikiExtractor::class)->getInfo($value);
            dd(!$info['wiki_redirect']);
            return (!$info['wiki_missing'] || !$info['wiki_redirect']);
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
