<?php

    namespace App\Models\Imports;

    use Illuminate\Database\Eloquent\Model;

    class OnsLocalAdmin extends Model
    {
        /**
         * @var array
         */
        protected $guarded = [];

        /**
         * @var bool
         */
        public $timestamps = false;

        /**
         * @var bool
         */
        public $incrementing = false;
    }