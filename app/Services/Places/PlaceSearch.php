<?php

    namespace App\Services\Places;

    use Illuminate\Support\Collection;

    class PlaceSearch
    {
        /**
         * @var array
         */
        protected $validOrderBy = [
            'name',
            'type',
            'ons_id',
            'os_id'
        ];

        /**
         * @var string
         */
        protected $defaultOrderBy = 'name';

        /**
         * @var array
         */
        protected $headings = [
            'name',
            'type',
            'wiki_title',
            'wikidata_id',
            'osm_id',
            'os_id',
            'ons_id',
            'geo_id'
        ];

        /**
         * @var string
         */
        protected $defaultOrder = 'asc';

        /**
         * @var array
         */
        protected $orders = ['asc', 'desc'];

        /**
         * @var array
         */
        protected $inputs = [];

        /**
         * GeoSearch constructor.
         *
         * @param array $inputs Form Inputs
         */
        public function __construct(array $inputs = [])
        {
            $this->inputs = $inputs;
        }

        /**
         * @param string $key
         * @return mixed|null
         */
        public function getInput(string $key)
        {
            return $this->inputs[$key] ?? null;
        }

        /**
         * @return string
         */
        public function getOrderBy(): string
        {
            return $this->getInput('order_by') ?: $this->defaultOrderBy;
        }

        /**
         * @return string
         */
        public function getOrder(): string
        {
            return $this->getInput('order') ?: $this->defaultOrder;
        }

        /**
         * @return string
         */
        public function getOrderOpposite(): string
        {
            if ($this->getOrder() === 'asc') {
                return 'desc';
            }

            return 'asc';
        }

        /**
         * @param string $prefix
         * @return Collection
         */
        public function getOrderByTranslated(string $prefix = 'placeshub'): Collection
        {
            return collect(transArray($this->validOrderBy, $prefix))->sort();
        }

        /**
         * @param string $prefix
         * @return Collection
         */
        public function getOrderTranslated(string $prefix = 'placeshub'): Collection
        {
            return collect(transArray($this->orders, $prefix))->sort();
        }

        /**
         * @param string $prefix
         * @return array
         */
        public function getHeadingsTranslated(string $prefix = 'placeshub'): array
        {
            return transArray($this->headings, $prefix);
        }

        /**
         * @return string
         */
        public function getDefaultOrderBy(): string
        {
            return $this->defaultOrderBy;
        }

        /**
         * @return string
         */
        public function getDefaultOrder(): string
        {
            return $this->defaultOrder;
        }

        /**
         * @return array
         */
        public function getValidOrderBy(): array
        {
            return $this->validOrderBy;
        }

        /**
         * @param array $override
         * @return array
         */
        public function getAppends($override = []): array
        {
            $appends = [
                'order_by' => $this->getOrderBy(),
                'order' => $this->getOrder(),
                'type' => $this->getInput('type'),
                'name' => $this->getInput('name')

            ];

            foreach ($override as $key => $value) {
                $appends[$key] = $value;
            }

            return $appends;
        }
    }