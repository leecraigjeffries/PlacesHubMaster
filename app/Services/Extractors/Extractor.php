<?php

    namespace App\Services\Extractors;

    class Extractor
    {
        /**
         * @var WikiExtractor
         */
        protected $wiki;

        public function __construct(
            WikiExtractor $wiki
        ) {
            $this->wiki = $wiki;
        }

        /**
         * @return WikiExtractor
         */
        public function wiki(): WikiExtractor
        {
            return $this->wiki;
        }

        /**
         * @param WikiExtractor $wiki
         * @return Extractor
         */
        public function setWiki(WikiExtractor $wiki): self
        {
            $this->wiki = $wiki;

            return $this;
        }


    }