<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_currency_to_site_settings.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('currency_code', 10)->default('NGN'); // NGN, USD, GBP...
            $table->string('currency_symbol', 10)->default('₦'); // ₦, $, £ ...
            $table->string('currency_position', 10)->default('before'); // before|after
            $table->string('thousand_separator', 5)->default(','); // , or .
            $table->string('decimal_separator', 5)->default('.'); // . or ,
            $table->unsignedTinyInteger('decimal_places')->default(0); // 0 for naira, 2 for usd etc.
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'currency_code',
                'currency_symbol',
                'currency_position',
                'thousand_separator',
                'decimal_separator',
                'decimal_places',
            ]);
        });
    }
};
