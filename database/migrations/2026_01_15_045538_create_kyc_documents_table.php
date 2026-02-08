<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kyc_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kyc_id')->constrained()->cascadeOnDelete();

            $table->string('type'); 
            // id_card | proof_of_income | proof_of_address | selfie

            $table->string('label')->nullable(); // e.g. "NIN Slip", "Utility Bill"
            $table->string('file_path');
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('size')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyc_documents');
    }
};
