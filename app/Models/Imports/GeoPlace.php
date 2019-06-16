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
        protected $adminTypes = [
            'adm1',
            'adm2',
            'adm3',
            'adm4'
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


        protected $childTypes = [
            'adm1' => ['adm2', 'rgn', 'ppl'],
            'adm2' => ['adm3', 'ppl'],
            'adm3' => ['adm4', 'ppl'],
            'adm4' => ['adm5', 'ppl'],
            'ppl' => []
        ];

        /**
         * @return array
         */
        public function getAdminTypes(): array
        {
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


    }