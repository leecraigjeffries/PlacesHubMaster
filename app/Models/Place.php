<?php

    namespace App\Models;

    use App\Traits\DefinesRelationships;
    use Cviebrock\EloquentSluggable\Sluggable;
    use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
    use Illuminate\Database\Eloquent\Model;

    class Place extends Model
    {
        use SpatialTrait, Sluggable, DefinesRelationships;

        /**
         * @var array
         */
        protected $casts = [
            'lat' => 'float',
            'lon' => 'float',
            'id' => 'string'
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


        protected static $types = [
            'country',
            'macro_region',
            'region',
            'macro_county',
            'county',
            'district',
            'local_admin',
            'locality',
            'hood'
        ];

        protected static $childTypes = [
            'country' => ['macro_region', 'region'],
            'macro_region' => ['region', 'macro_county', 'county', 'district'],
            'region' => ['macro_county', 'county', 'district', 'local_admin'],
            'macro_county' => ['county', 'district', 'local_admin', 'locality', 'hood'],
            'county' => ['district', 'local_admin', 'locality', 'hood'],
            'district' => ['local_admin', 'locality', 'hood'],
            'local_admin' => ['locality', 'hood'],
            'locality' => ['hood'],
            'hood' => []
        ];

        protected static $ratios = [
            'country' => 6,
            'dependency' => 4,
            'macro_region' => 3,
            'region' => 1.1,
            'macro_county' => 1,
            'county' => 1,
            'district' => 0.08,
            'local_admin' => 0.02,
            'locality' => 0.02,
            'hood' => 0.02
        ];

        /**
         * @param array $types
         */
        public static function setTypes(array $types): void
        {
            self::$types = $types;
        }

        /**
         * @return array
         */
        public static function getRatios(): array
        {
            return self::$ratios;
        }

        /**
         * @param array $ratios
         */
        public static function setRatios(array $ratios): void
        {
            self::$ratios = $ratios;
        }

        /**
         * @return array
         */
        public function sluggable(): array
        {
            return [
                'slug' => [
                    'source' => 'name'
                ]
            ];
        }

        /**
         * @return string
         */
        public function getRouteKeyName(): string
        {
            return 'slug';
        }

        /**
         * Map Ratio for zooming on OSM.
         *
         * @return mixed
         */
        public function getRatio()
        {
            return self::$ratios[$this->type];
        }

        /**
         * Associated Types (using "type")
         *
         * @param bool $withId
         *
         * @return array
         */
        public static function getTypes(bool $withId = false): array
        {
            if ($withId === true) {
                foreach (static::$types as $type) {
                    $to_return[] = $type . '_id';
                }
            }

            return $to_return ?? static::$types;
        }


        /**
         * @return array
         */
        public function getChildTypes(): array
        {
            return static::$childTypes[$this->type];
        }

        /**
         * @return array
         */
        public function getParentTypes(): array
        {
            foreach (static::$childTypes as $parent_type => $children) {
                if (in_array($this->type, $children, true)) {
                    $parents[] = $parent_type;
                }
            }

            return $parents ?? [];
        }

        /**
         * @param string $type
         * @return bool
         */
        public function isValidChildType(string $type): bool
        {
            return in_array($type, $this->getChildTypes(), true);
        }

        /**
         * @param bool $withId
         * @return array
         */
        private static function getTypesWithoutLastElement(bool $withId = false): array
        {
            $types = static::getTypes($withId);

            array_pop($types);

            return $types;
        }

        /**
         * @return mixed
         */
        public function siblings()
        {
            $query = $this->where('id', '!=', $this->id)
                ->where('type', $this->type);

            foreach (self::getTypesWithoutLastElement(true) as $col) {
                $query = $query->where($col, $this->{$col});
            }

            return $query->orderBy('name')->get();
        }

        /**
         * Next place using Name attribute
         *
         * @return mixed
         */
        public function next()
        {
            $query = $this->where('id', '<>', $this->id)
                ->where('type', $this->type)
                ->where('name', '>', $this->name);

            foreach (self::getTypesWithoutLastElement(true) as $col) {
                $query = $query->where($col, $this->{$col});
            }

            return $query->orderBy('name')->first();
        }

        /**
         * Previous using Name attribute
         *
         * @return mixed
         */
        public function previous()
        {
            $query = $this->where('id', '<>', $this->id)
                ->where('type', $this->type)
                ->where('name', '<', $this->name);

            foreach (self::getTypesWithoutLastElement(true) as $col) {
                $query = $query->where($col, $this->{$col});
            }

            return $query->orderBy('name', 'desc')->first();
        }

        /**
         * @param bool $includeThis
         * @return array
         */
        public function juniorColumns(bool $includeThis = false): array
        {
            return array_slice(
                static::getTypesWithoutLastElement(true),
                array_search($this->type, self::getTypes(), true) + ($includeThis === false ? 1 : 0)
            );
        }

        /**
         * @param bool $includeThis
         * @return array
         */
        public function juniorTypes(bool $includeThis = false): array
        {
            return array_slice(
                static::getTypesWithoutLastElement(),
                array_search($this->type, self::getTypes(), true) + ($includeThis === false ? 1 : 0)
            );
        }

        /**
         * @param bool $includeThis
         * @return array
         */
        public function seniorColumns(bool $includeThis = false): array
        {
            return array_slice(
                static::getTypesWithoutLastElement(true),
                0,
                array_search($this->type, static::getTypes(), true) + ($includeThis === false ? 0 : 1)
            );
        }

        /**
         * @param bool $includeThis
         * @return array
         */
        public function seniorColumnsReversed(bool $includeThis = false): array
        {
            return array_reverse($this->seniorColumns($includeThis));
        }

        /**
         * Get type attribute as column.
         *
         * @return string
         */
        public function getTypeColumnAttribute(): string
        {
            return $this->type . '_id';
        }
    }