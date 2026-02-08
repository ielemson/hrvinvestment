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
        Schema::create('loan_workflow_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->cascadeOnDelete();

            $table->string('level_key');
            $table->enum('status', ['under_review', 'reviewed', 'approved'])->default('under_review');

            $table->text('notes')->nullable();
            $table->longText('content')->nullable();

            $table->foreignId('edited_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('edited_at')->nullable();

            $table->timestamps();
            $table->unique(['loan_id', 'level_key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_workflow_levels');
    }
};
