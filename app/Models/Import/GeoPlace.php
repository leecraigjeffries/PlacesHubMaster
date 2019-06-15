<?php

    namespace App\Models\Import;

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
            return $this->belongsTo(static::class)->whereType('ADM1');
        }

        public function adm2()
        {
            return $this->belongsTo(static::class)->whereType('ADM2');
        }

        public function adm3()
        {
            return $this->belongsTo(static::class)->whereType('ADM3');
        }

        public function adm4()
        {
            return $this->belongsTo(static::class)->whereType('ADM4');
        }

        public function adm5()
        {
            return $this->belongsTo(static::class)->whereType('ADM5');
        }


    }