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
    public function up(): void
    {
        Schema::create('osm_places', static function (Blueprint $table) {
            $table->bigInteger('id')->unsigned();
            $table->string('name')->collation('utf8mb4_unicode_ci');
            $table->string('network_type', 8)->collation('ascii_bin');
            $table->string('class', 8)->collation('ascii_bin');
            $table->string('osm_type', 14)->collation('ascii_bin');
            $table->string('city_name')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('county_name')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('state_name')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('wikidata_id')->collation('ascii_bin')->nullable();
            $table->string('wiki_title')->collation('utf8mb4_unicode_ci')->nullable();
            $table->decimal('lat', 8, 6);
            $table->decimal('lon', 9, 6);
            $table->point('point');

            $table->index(['id', 'network_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('osm_places');
    }
}
