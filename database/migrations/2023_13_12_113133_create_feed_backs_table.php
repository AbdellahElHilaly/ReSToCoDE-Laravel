<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('feed_backs', function (Blueprint $table) {
            $table->id();
            $table->integer('body');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('menu_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('set null');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('feed_backs');
    }
};
