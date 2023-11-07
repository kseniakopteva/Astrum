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
            $table->integer('post_frame_id')->unsigned();
            $table->integer('user_id')->unsigned();
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
