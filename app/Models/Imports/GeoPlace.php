<?php

    namespace App\Models\Imports;

    use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
    use Illuminate\Database\Eloquent\Model;

    class GeoPlace extends Model
    {
        use SpatialTrait;

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
         * @var array
         */
        protected $adminTypes = [
            'adm1',
            'adm2',
            'adm3',
            'adm4'
        ];

        /**
         * @var array
         */
        protected $types = [
            'adm1',
            'adm2',
            'adm3',
            'adm4',
            'adm5',
            'ppl'
        ];

        /**
         * @var array
         */
        protected $childTypes = [
            'adm1' => ['adm2', 'rgn', 'admd', 'ppl'],
            'adm2' => ['adm3', 'admd', 'ppl'],
            'adm3' => ['adm4', 'ppl'],
            'adm4' => ['adm5', 'ppl'],
            'adm5' => [],
            'rgn' => [],
            'admd' => [],
            'ppl' => []
        ];

        /**
         * @var array
         */
        protected $ratios = [
            'adm1' => 4,
            'adm2' => 3,
            'adm3' => 0.5,
            'adm4' => 0.4,
            'adm5' => 0.3,
            'admd' => 1,
            'rgn' => 2,
            'ppl' => 0.05
        ];

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
         * @return GeoPlace
         */
        public function setAdminTypes(array $adminTypes): self
        {
            $this->adminTypes = $adminTypes;

            return $this;
        }

        public function adm1()
        {
            return $this->belongsTo(static::class)->whereType('adm1');
        }

        public function adm2()
        {
            return $this->belongsTo(static::class)->whereType('adm2');
        }

        public function adm3()
        {
            return $this->belongsTo(static::class)->whereType('adm3');
        }

        public function adm4()
        {
            return $this->belongsTo(static::class)->whereType('adm4');
        }

        public function adm3s()
        {
            return $this->hasMany(static::class, $this->type_column)
                ->where('type', 'adm3');
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