<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnsLocalAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('ons_local_admins', function (Blueprint $table) {
            $table->char('id', 9)->collation('ascii_bin')->primary();
            $table->string('name', 191)->collation('utf8mb4_unicode_ci');
            $table->char('district_id', 9)->collation('ascii_bin')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('ons_local_admins');
    }
}
