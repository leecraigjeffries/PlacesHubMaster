<?php

    namespace App\Models;

    use App\Exceptions\Models\Place\EndBeforeStartException;
    use App\Traits\DefinesRelationships;
    use Cviebrock\EloquentSluggable\Sluggable;
    use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialExpression;
    use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
    use Grimzy\LaravelMysqlSpatial\Types\Point;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class Place extends Model
    {
        use SpatialTrait, Sluggable, DefinesRelationships, SoftDeletes;

        /**
         * @var array
         */
        protected $casts = [
            'lat' => 'float',
            'lon' => 'float',
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
        public function ratio()
        {
            return self::$ratios[$this->type];
        }

        /**
         * @param string $start
         * @param string $end
         * @param bool $incStart
         * @param bool $incEnd
         * @return array
         */
        public static function sliceTypes(
            string $start,
            string $end,
            bool $incStart = false,
            bool $incEnd = false
        ): array {
            if ($start === $end) {
                return [];
            }

            if (array_search($start, self::types(), true) > array_search($end, self::types(), true)) {
                return [];
            }

            $tempArray = array_slice(
                self::types(),
                array_search($start, self::types(), true) + ($incStart ? 0 : 1)
            );

            $tempArray = array_slice(
                $tempArray,
                0,
                array_search($end, $tempArray, true) + ($incEnd ? 1 : 0)
            );

            return $tempArray;
        }

        /**
         * Get types.
         *
         * @param bool $withId
         *
         * @return array
         */
        public static function types(bool $withId = false): array
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
        public function childTypes(): array
        {
            return static::$childTypes[$this->type];
        }

        /**
         * @param string|null $type
         * @return array
         */
        public function parentTypes(?string $type = null): array
        {
            $type = $type ?: $this->type;

            foreach (static::$childTypes as $parent_type => $children) {
                if (in_array($type, $children, true)) {
                    $parents[] = $parent_type;
                }
            }

            return $parents ?? [];
        }

        /**
         * @return Place|null
         */
        public function parent(): ?Place
        {
            foreach ($this->seniorColumnsReversed() as $col) {
                if ($this->$col) {
                    return $this->find($this->$col);
                }
            }

            return null;
        }

        /**
         * @param string $type
         * @return bool
         */
        public function isValidChildType(string $type): bool
        {
            return in_array($type, $this->childTypes(), true);
        }

        /**
         * @param bool $withId
         * @return array
         */
        public static function typesWithoutLastElement(bool $withId = false): array
        {
            $types = static::types($withId);

            array_pop($types);

            return $types;
        }

        /**
         * @return mixed
         */
        public function siblings()
        {
            $query = $this->where('id', '<>', $this->id)
                ->whereType($this->type);

            foreach (self::typesWithoutLastElement(true) as $col) {
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

            foreach (self::typesWithoutLastElement(true) as $col) {
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

            foreach (self::typesWithoutLastElement(true) as $col) {
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
                static::typesWithoutLastElement(true),
                array_search($this->type, self::types(), true) + ($includeThis === false ? 1 : 0)
            );
        }

        /**
         * @param bool $includeThis
         * @return array
         */
        public function juniorTypes(bool $includeThis = false): array
        {
            return array_slice(
                static::types(),
                array_search($this->type, self::types(), true) + ($includeThis === false ? 1 : 0)
            );
        }

        /**
         * @param bool $includeThis
         * @return array
         */
        public function seniorTypes(bool $includeThis = false): array
        {
            return array_slice(
                static::types(),
                0,
                array_search($this->type, static::types(), true) + ($includeThis === false ? 0 : 1)
            );
        }

        /**
         * @param bool $includeThis
         * @return array
         */
        public function seniorColumns(bool $includeThis = false): array
        {
            return array_slice(
                static::types(true),
                0,
                array_search($this->type, static::types(), true) + ($includeThis === false ? 0 : 1)
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

        /**
         * @return array
         */
        public function childCount(): array
        {
            $counts = [];
            foreach ($this->juniorTypes() as $juniorType) {
                $query = $this->where($this->type_column, $this->id)
                    ->whereType($juniorType);

                foreach ($this::sliceTypes($this->type, $juniorType) as $middleType) {
                    $query = $query->whereNull("{$middleType}_id");
                }

                $counts[$juniorType] = $query->count();
            }

            return $counts;
        }

        public function setLatAttribute($lat): void
        {
            $this->attributes['point'] = new SpatialExpression(new Point(
                $lat,
                $this->attribute['lon']
            ));
        }
    }