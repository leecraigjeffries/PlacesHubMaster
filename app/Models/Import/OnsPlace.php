<?php

    namespace App\Models\Import;

    use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
    use Illuminate\Database\Eloquent\Model;

    class OnsPlace extends Model
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


        public function county()
        {
            return $this->belongsTo(static::class, 'county_id', 'ons_id')->whereType('CTY');
        }

        public function district()
        {
            return $this->belongsTo(static::class, 'district_id', 'ons_id')->whereIn('type', [
                'CA',
                'UA',
                'NMD',
                'MD',
                'LONB'
            ]);
        }

    }