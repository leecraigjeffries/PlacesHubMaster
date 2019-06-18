<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnsPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('ons_places', static function (Blueprint $table) {
            $table->increments('id');
            $table->char('ipn_id', 10)->index()->collation('ascii_bin');
            $table->char('ons_id', 9)->nullable()->index()->collation('utf8mb4_unicode_ci');
            $table->string('name')->collation('utf8mb4_unicode_ci');
            $table->string('type', 12)->collation('ascii_bin');
            $table->string('ons_type', 5)->collation('ascii_bin');
            $table->char('district_id', 9)->collation('ascii_bin')->nullable()->index();
            $table->string('district_name')->collation('utf8mb4_unicode_ci')->nullable();
            $table->char('county_id', 9)->collation('ascii_bin')->nullable()->index();
            $table->string('county_name')->collation('utf8mb4_unicode_ci')->nullable();
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
    public function down(): void
    {
        Schema::dropIfExists('ons_places');
    }
}
