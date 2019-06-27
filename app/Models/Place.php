<?php

    namespace App\Models;

    use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
    use Illuminate\Database\Eloquent\Model;

    class Place extends Model
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

    }