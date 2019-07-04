<?php

    namespace App\Rules;

    use Illuminate\Contracts\Validation\Rule;

    class ValidWikiChars implements Rule
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
            $invalid_chars = ['#', '|', '<', '>', '[', ']'];

            foreach ($invalid_chars as $char) {
                if (strpos($value, $char) !== false) {
                    return false;
                }
            }

            return true;
        }

        /**
         * Get the validation error message.
         *
         * @return string
         */
        public function message(): string
        {
            return __('validation.invalid_wiki_chars');
        }
    }
