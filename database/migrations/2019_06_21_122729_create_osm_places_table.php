<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOsmPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('osm_places', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary();
            $table->string('name')->collation('utf8mb4_unicode_ci');
            $table->string('network_type', 8)->collation('ascii_bin');
            $table->string('class', 8)->collation('ascii_bin');
            $table->string('osm_type', 14)->collation('ascii_bin');
            $table->string('city_name')->collation('utf8mb4_unicode_ci')->nullable();
            $table->bigInteger('city_id')->unsigned()->index()->nullable();
            $table->string('county_name')->collation('utf8mb4_unicode_ci')->nullable();
            $table->bigInteger('county_id')->unsigned()->index()->nullable();
            $table->string('state_name')->collation('utf8mb4_unicode_ci')->nullable();
            $table->bigInteger('state_id')->unsigned()->index()->nullable();
            $table->string('wikidata_id')->collation('ascii_bin')->nullable();
            $table->string('wiki_title')->collation('utf8mb4_unicode_ci')->nullable();
            $table->decimal('lat', 8, 6);
            $table->decimal('lon', 9, 6);
            $table->point('point');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('osm_places');
    }
}
