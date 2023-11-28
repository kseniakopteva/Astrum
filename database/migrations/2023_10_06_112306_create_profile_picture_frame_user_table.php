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
        Schema::create('profile_picture_frame_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_picture_frame_id')->constrained('profile_picture_frames')->unsigned();
            $table->foreignId('user_id')->constrained('users')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_picture_frame_user');
    }
};
