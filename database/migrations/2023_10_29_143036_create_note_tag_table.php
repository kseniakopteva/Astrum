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
        Schema::create('note_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->constrained('notes')->unsigned();
            $table->foreignId('tag_id')->constrained('tags')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_tag');
    }
};
