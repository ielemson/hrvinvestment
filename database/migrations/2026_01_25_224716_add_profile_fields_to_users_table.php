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
        Schema::table('users', function (Blueprint $table) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone', 25)->nullable()->after('email');
                $table->string('address')->nullable()->after('phone');
                $table->boolean('notify_email')->default(true)->after('address');
                $table->boolean('notify_app')->default(true)->after('notify_email');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'notify_email', 'notify_app']);
        });
    }
};
