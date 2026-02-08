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
        Schema::table('loans', function (Blueprint $table) {
            $table->string('repayment_method', 30)->nullable()->after('tenure_months');   // bank_transfer|direct_debit|wallet
            $table->string('employment_type', 30)->nullable()->after('repayment_method'); // salary|business|freelance|agriculture|other
            $table->string('income_band', 30)->nullable()->after('employment_type');      // below_100k|100k_300k|300k_700k|above_700k
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['repayment_method', 'employment_type', 'income_band']);
        });
    }
};
