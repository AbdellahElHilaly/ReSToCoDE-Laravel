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
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            // all can be null
            $table->string('ip');
            $table->string('device')->nullable();
            $table->string('platform')->nullable();
            $table->string('browser')->nullable();
            $table->string('expires_at');
            $table->string('location')->nullable();
            $table->string('network')->nullable();
            $table->string('code')->nullable();

            $table->timestamps();
        });
    }




    public function down(): void
    {
        Schema::dropIfExists('tokens');
    }
};
