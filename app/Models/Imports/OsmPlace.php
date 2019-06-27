<?php

    namespace App\Models\Imports;

    use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
    use Illuminate\Database\Eloquent\Model;

    class OsmPlace extends Model
    {
        use SpatialTrait;

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

        protected $adminTypes = [
            'city',
            'county',
            'state'
        ];

        /**
         * @return int
         */
        public function getRatio(): int
        {
            return 4;
        }

        /**
         * @return array
         */
        public function getAdminTypes(): array
        {
            return $this->adminTypes;
        }
    }