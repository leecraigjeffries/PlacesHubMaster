<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeoPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('geo_places', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('geo_code')->collation('ascii_bin')->nullable()->index();
            $table->string('geo_code_full')->collation('ascii_bin')->nullable();
            $table->string('name');
            $table->string('type', 5)->collation('ascii_bin');
            $table->string('geo_type', 5)->collation('ascii_bin');
            $table->decimal('lat', 8, 6);
            $table->decimal('lon', 9, 6);
            $table->point('point')->nullable();
            $table->integer('adm1_id')->unsigned()->index()->nullable();
            $table->string('adm1_code')->collation('ascii_bin')->nullable()->index();
            $table->string('adm1_name')->nullable();
            $table->integer('adm2_id')->unsigned()->index()->nullable();
            $table->string('adm2_code')->collation('ascii_bin')->nullable()->index();
            $table->string('adm2_name')->nullable();
            $table->integer('adm3_id')->unsigned()->index()->nullable();
            $table->string('adm3_code')->collation('ascii_bin')->nullable()->index();
            $table->string('adm3_name')->nullable();
            $table->integer('adm4_id')->unsigned()->index()->nullable();
            $table->string('adm4_code')->collation('ascii_bin')->nullable()->index();
            $table->string('adm4_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('geo_places');
    }
}
