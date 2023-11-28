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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->string('slug')->unique();
            $table->text('notebody');
            $table->boolean('removed')->default(false);
            $table->timestamps();
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
