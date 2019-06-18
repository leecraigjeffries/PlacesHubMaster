<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateOsPlacesTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up(): void
        {
            Schema::create('os_places', static function (Blueprint $table) {
                $table->string('id', '16')->collation('ascii_bin')->primary();
                $table->string('name')->collation('utf8mb4_unicode_ci');
                $table->string('type', 12)->collation('ascii_bin');
                $table->string('os_type', 22)->collation('ascii_bin');
                $table->bigInteger('district_id')->unsigned()->index()->nullable();
                $table->string('district_name')->collation('utf8mb4_unicode_ci')->nullable();
                $table->string('district_type')->collation('ascii_bin')->nullable();
                $table->bigInteger('county_id')->unsigned()->index()->nullable();
                $table->string('county_name')->collation('utf8mb4_unicode_ci')->nullable();
                $table->string('county_type')->collation('ascii_bin')->nullable();
                $table->bigInteger('region_id')->unsigned()->index()->nullable();
                $table->string('region_name')->collation('utf8mb4_unicode_ci')->nullable();
                $table->string('region_type')->collation('ascii_bin')->nullable();
                $table->string('macro_region_id', 16)->collation('ascii_bin')->index()->nullable();
                $table->string('macro_region_name')->collation('utf8mb4_unicode_ci')->nullable();
                $table->string('macro_region_type', 7)->collation('ascii_bin')->nullable();
                $table->decimal('lat', 8, 6);
                $table->decimal('lon', 9, 6);
                $table->point('point');
                $table->integer('geonames_id')->unsigned()->index()->nullable();
                $table->string('wiki_title')->collation('utf8mb4_unicode_ci')->nullable();
                $table->char('os_grid_id')->collation('ascii_bin')->nullable();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down(): void
        {
            Schema::dropIfExists('os_places');
        }
    }
