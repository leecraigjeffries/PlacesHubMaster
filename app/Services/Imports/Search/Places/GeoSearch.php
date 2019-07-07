<?php


    namespace App\Services\Imports\Search\Places;


    use Illuminate\Support\Collection;

    class GeoSearch
    {
        /**
         * @var array
         */
        protected $validOrderBy = [
            'name',
            'geo_type',
            'geo_code',
            'geo_code_full',
            'adm1_name',
            'adm2_name',
            'adm3_name',
            'adm4_name'
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
            'geo_type',
            'geo_code',
            'geo_code_full'
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
                'geo_type' => $this->getInput('geo_type'),
                'adm1_name' => $this->getInput('adm1_name'),
                'adm2_name' => $this->getInput('adm2_name'),
                'adm3_name' => $this->getInput('adm3_name'),
                'adm4_name' => $this->getInput('adm4_name'),
                'name' => $this->getInput('name')

            ];

            foreach ($override as $key => $value) {
                $appends[$key] = $value;
            }

            return $appends;
        }
    }