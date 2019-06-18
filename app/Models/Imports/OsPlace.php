<?php

    namespace App\Models\Imports;

    use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
    use Illuminate\Database\Eloquent\Model;

    class OsPlace extends Model
    {
        use SpatialTrait;

        /**
         * @var array
         */
        protected $casts = [
            'lat' => 'float',
            'lon' => 'float',
            'id' => 'string',
            'district_id' => 'string',
            'county_id' => 'string',
            'region_id' => 'string'
        ];

        /**
         * @var array
         */
        protected $adminTypes = [
            'county',
            'district'
        ];

        /**
         * @var array
         */
        protected $types = [
            'macro_region',
            'region',
            'macro_county',
            'county',
            'district',
            'local_admin',
            'locality'
        ];

        /**
         * @var array
         */
        protected $childTypes = [
            'region' => [],
            'macro_county' => [],
            'county' => ['district', 'local_admin', 'bua', 'buasd', 'locality'],
            'district' => ['local_admin', 'bua', 'buasd', 'locality'],
            'local_admin' => [],
            'bua' => [],
            'buasd' => [],
            'locality' => []
        ];

        /**
         * @var array
         */
        protected $ratios = [
            'region' => 4,
            'macro_county' => 3,
            'county' => 0.5,
            'district' => 0.4,
            'local_admin' => 0.3,
            'bua' => 1,
            'buasd' => 2,
            'locality' => 0.05
        ];

        /**
         * @var array
         */
        protected $guarded = [];

        /**
         * @var array
         */
        protected $spatialFields = [
            'point'
        ];

        /**
         * @var bool
         */
        public $timestamps = false;

        /**
         * @var bool
         */
        public $incrementing = false;


        public function county()
        {
            return $this->belongsTo(static::class)->whereType('county');
        }

        public function district()
        {
            return $this->belongsTo(static::class)->whereType('district');
        }

        /**
         * @param bool $withId
         * @return array
         */
        public function getAdminTypes(bool $withId = false): array
        {
            if ($withId === true) {
                foreach ($this->adminTypes as $type) {
                    $withIds[] = $type . '_id';
                }

                return $withIds ?? [];
            }

            return $this->adminTypes;
        }

        /**
         * @param array $adminTypes
         * @return OnsPlace
         */
        public function setAdminTypes(array $adminTypes): self
        {
            $this->adminTypes = $adminTypes;

            return $this;
        }

        public function childTypes(?string $type = null): array
        {
            return $this->childTypes[$type ?: $this->type];
        }

        public function juniorAdminTypes(bool $include = false): array
        {
            return array_slice($this->getAdminTypes(),
                array_search($this->type, $this->getAdminTypes(), true) + ($include === false ? 1 : 0));
        }


        public function parentTypes(?string $type = null): array
        {
            foreach ($this->childTypes as $parentType => $children) {
                if (in_array($type ?: $this->type, $children, true)) {
                    $parents[] = $parentType;
                }
            }

            return $parents ?? [];
        }

        public function parentTypesReversed(): array
        {
            return array_reverse($this->parentTypes());
        }

        /**
         * Get type attribute as column
         *
         * @return string
         */
        public function getTypeColumnAttribute(): string
        {
            return $this->type . '_id';
        }

        /**
         * @return array
         */
        public function getTypes(): array
        {
            return $this->types;
        }

        /**
         * @param array $types
         */
        public function setTypes(array $types): void
        {
            $this->types = $types;
        }

        /**
         * @return float
         */
        public function getRatio(): float
        {
            return $this->ratios[$this->type];
        }

        /**
         * @param array $ratios
         * @return GeoPlace
         */
        public function setRatios(array $ratios): self
        {
            $this->ratios = $ratios;

            return $this;
        }

    }