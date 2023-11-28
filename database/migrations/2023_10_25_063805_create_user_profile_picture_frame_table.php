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
        Schema::create('user_profile_picture_frame', function (Blueprint $table) {
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
        Schema::dropIfExists('user_profile_picture_frame');
    }
};
