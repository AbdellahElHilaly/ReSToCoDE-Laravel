<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('meals', function (Blueprint $table) {

            $table->id();
            $table->string('name')->uniqid();
            $table->text('description');
            $table->unsignedBigInteger('user_id');
            $table->integer('quantity');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};

// what is the best directory for add the images's folder ?

// i added image in public/images/meals/1.jpg in my laravel project

// how can i show it in the browser ?


