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
        Schema::create('news', function (Blueprint $table) {
             $table->id();
        $table->string('api_source')->nullable(); // NewsAPI, GNews etc
        $table->string('external_id')->nullable()->index();
        $table->string('source_name')->nullable();
        $table->string('source_url'); // ORIGINAL LINK
        $table->string('title');
        $table->string('slug')->nullable()->index();
        $table->text('summary')->nullable();
        $table->string('image_url')->nullable();
        $table->timestamp('published_at')->nullable();
        $table->timestamp('fetched_at')->nullable();
        $table->boolean('is_active')->default(true);
        $table->json('raw_payload')->nullable();
        $table->timestamps();

        // Prevent duplicates
        $table->unique(['source_url']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
};
