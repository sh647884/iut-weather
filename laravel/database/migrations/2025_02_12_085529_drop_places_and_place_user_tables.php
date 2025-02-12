<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('place_user');
        Schema::dropIfExists('places');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('places', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('place_user', function ($table) {
            $table->unsignedBigInteger('place_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_favorite')->default(false);
            $table->boolean('send_forecast')->default(false);
            $table->timestamps();

            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->primary(['place_id', 'user_id']);
        });
    }
};
