<?php

    namespace App\Traits;

    trait DefinesRelationships
    {
        public function country()
        {
            return $this->belongsTo(static::class)->whereType('country');
        }

        public function dependency()
        {
            return $this->belongsTo(static::class)->whereType('dependency');
        }

        public function macro_region()
        {
            return $this->belongsTo(static::class)->whereType('macro_region');
        }

        public function region()
        {
            return $this->belongsTo(static::class)->whereType('region');
        }

        public function macro_county()
        {
            return $this->belongsTo(static::class)->whereType('macro_county');
        }

        public function county()
        {
            return $this->belongsTo(static::class)->whereType('county');
        }

        public function district()
        {
            return $this->belongsTo(static::class)->whereType('district');
        }

        public function local_admin()
        {
            return $this->belongsTo(static::class)->whereType('local_admin');
        }

        public function locality()
        {
            return $this->belongsTo(static::class)->whereType('locality');
        }

        public function hood()
        {
            return $this->belongsTo(static::class)->whereType('hood');
        }

        public function countries()
        {
            return $this->hasMany(static::class, $this->type_column)
                ->where('type', 'country');
        }

        public function dependencies()
        {
            return $this->hasMany(static::class, $this->type_column)
                ->where('type', 'dependency');
        }

        public function macro_regions()
        {
            return $this->hasMany(static::class, $this->type_column)
                ->where('type', 'macro_region');
        }

        public function regions()
        {
            return $this->hasMany(static::class, $this->type_column)
                ->where('type', 'region');
        }

        public function macro_counties()
        {
            return $this->hasMany(static::class, $this->type_column)
                ->where('type', 'macro_county');
        }

        public function counties()
        {
            return $this->hasMany(static::class, $this->type_column)
                ->where('type', 'county');
        }

        public function districts()
        {
            return $this->hasMany(static::class, $this->type_column)
                ->where('type', 'district');
        }

        public function local_admins()
        {
            return $this->hasMany(static::class, $this->type_column)
                ->where('type', 'local_admin');
        }

        public function localities()
        {
            return $this->hasMany(static::class, $this->type_column)
                ->where('type', 'locality');
        }

        public function hoods()
        {
            return $this->hasMany(static::class, $this->type_column)
                ->where('type', 'hood');
        }
    }