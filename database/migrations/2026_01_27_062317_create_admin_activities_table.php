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
        Schema::create('admin_activities', function (Blueprint $table) {
            $table->id();
            $table->string('activity_id')->unique();  // #A-123
            $table->foreignId('admin_user_id')->constrained('users')->onDelete('cascade');
            $table->string('action');  // "KYC Approved", "Loan Reviewed"
            $table->string('target_type');  // "Kyc", "Loan", "User"
            $table->unsignedBigInteger('target_id');  // Related record ID
            $table->string('target_user');  // "John Doe (Borrower)"
            $table->string('status')->default('completed');  // "approved", "rejected", "pending"
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_activities');
    }
};
