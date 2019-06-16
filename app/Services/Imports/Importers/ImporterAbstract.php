<?php

    namespace App\Services\Imports\Importers;

    abstract class ImporterAbstract
    {
        /**
         * Get Filepath.
         *
         * @return string
         */
        public function getFilePath(): string
        {
            return storage_path($this->filePath);
        }

        /**
         * Directory is empty.
         *
         * @return bool
         */
        public function dirIsEmpty(): bool
        {
            return dir_is_empty($this->getFilePath());
        }

        /**
         * Return end of string after a given character.
         *
         * @param string $str
         * @param string $delimiter
         *
         * @return string|null
         */
        protected function endOfString(string $str, string $delimiter = '\/'): ?string
        {
            preg_match("#[^{$delimiter}]*$#", $str, $match, PREG_UNMATCHED_AS_NULL);

            return $match[0] ?: null;
        }

        public function fileOrDirExists()
        {
            return (is_dir($this->getFilePath()) && !$this->dirIsEmpty()) || (is_file($this->getFilePath()) && file_exists($this->getFilePath()));
        }

        /**
         * Import.
         *
         * @param bool $truncate
         * @return bool
         */
        public function import(bool $truncate): bool
        {
            if ($this->fileOrDirExists()) {
                return $this->importToDb($truncate);
            }

            return false;
        }
    }