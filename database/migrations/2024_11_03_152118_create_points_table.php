<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('points', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('name');
            $table->string('address');
            $table->text('description');
            $table->string('youtube_link')->nullable();
            $table->string('tg_link')->nullable();
            $table->string('coordinates');
            $table->foreignId('filter_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_visible')->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points');
    }
};
