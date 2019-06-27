<?php

    namespace App\Services\Imports\Search\Places;

    use Illuminate\Support\Collection;

    class OnsSearch
    {
        /**
         * @var array
         */
        protected $validOrderBy = [
            'name',
            'ons_type',
            'ipn_id',
            'ons_id',
            'district_name',
            'county_name'
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
            'ipn_id',
            'ons_id',
            'ons_type'
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
            $appends = $override + [
                'order_by' => $this->getOrderBy(),
                'order' => $this->getOrder(),
                'ons_type' => $this->getInput('ons_type'),
                'county_name' => $this->getInput('county_name'),
                'district_name' => $this->getInput('district_name')
            ];

            return $appends;
        }
    }