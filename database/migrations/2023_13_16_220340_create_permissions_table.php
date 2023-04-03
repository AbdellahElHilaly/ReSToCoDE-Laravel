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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pcontroller_id');
            $table->unsignedBigInteger('pmethode_id');

            $table->timestamps();


            $table->foreign('pcontroller_id')->references('id')->on('pcontrollers');
            $table->foreign('pmethode_id')->references('id')->on('pmethodes');
            $table->foreign('user_id')->references('id')->on('users');

            // don't repeat yourself (row) unique

            $table->unique(['user_id', 'pcontroller_id', 'pmethode_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
