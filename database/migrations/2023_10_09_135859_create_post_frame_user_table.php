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
        Schema::create('post_frame_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_frame_id')->constrained('post_frames')->unsigned();
            $table->foreignId('user_id')->constrained('users')->unsigned();
            $table->unsignedInteger('amount')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_frame_user');
    }
};
