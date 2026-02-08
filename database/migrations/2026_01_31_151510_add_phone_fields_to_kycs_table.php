<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kycs', function (Blueprint $table) {
            $table->string('phone_country_code', 10)->nullable();   // +234
            $table->string('phone_national', 30)->nullable();       // 8012345678
            $table->string('phone_e164', 40)->nullable();           // +2348012345678 (STANDARD)
            $table->string('phone_country_iso', 5)->nullable();     // NG
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kycs', function (Blueprint $table) {
            //
        });
    }
};
