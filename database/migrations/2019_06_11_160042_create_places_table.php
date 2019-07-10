<?php

    use App\Models\Place;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreatePlacesTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up(): void
        {
            Schema::create('places', static function (Blueprint $table) {
                $table->increments('id');

                foreach (Place::typesWithoutLastElement(true) as $column) {
                    $table->integer($column)->unsigned()->nullable()->index();
                }

                $table->string('type', 12)->collation('ascii_bin');
                $table->string('type_2', 12)->collation('ascii_bin')->nullable();
                $table->string('name')->collation('utf8mb4_unicode_ci');
                $table->string('official_name')->collation('utf8mb4_unicode_ci')->nullable();
                $table->string('wiki_title')->collation('utf8mb4_bin')->nullable();
                $table->string('wikidata_id')->collation('ascii_bin')->nullable();
                $table->integer('geo_id')->unsigned()->nullable();
                $table->integer('geo_id_2')->unsigned()->nullable();
                $table->integer('geo_id_3')->unsigned()->nullable();
                $table->integer('geo_id_4')->unsigned()->nullable();
                $table->bigInteger('osm_id')->unsigned()->nullable();
                $table->string('os_id', 23)->collation('utf8mb4_bin')->nullable();
                $table->char('ons_id', 9)->collation('ascii_bin')->nullable();
                $table->char('ipn_id', 10)->collation('ascii_bin')->nullable();
                $table->string('slug')->collation('ascii_bin')->index();
                $table->decimal('lat', 8, 6)->nullable();
                $table->decimal('lon', 9, 6)->nullable();
                $table->point('point')->nullable();
                $table->polygon('polygon')->nullable();
                $table->multiPolygon('multipolygon')->nullable();
                $table->char('iso3166_2')->collation('ascii_bin')->nullable();
                $table->text('notes')->collation('utf8mb4_unicode_ci')->nullable();
                $table->softDeletes();
                $table->string('delete_reason_type')->collation('ascii_bin')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down(): void
        {
            Schema::dropIfExists('places');
        }
    }
