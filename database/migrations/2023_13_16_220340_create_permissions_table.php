<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

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

            $table->unique(['user_id', 'pcontroller_id', 'pmethode_id']);
/*
            permissions is a pivot table between users and pcontrollers and pmethodes

            *UML : relationships + cardinality

            forme :  teb1 (crd_1 .. card_2) [flesh derection]------------------{relationship}------------------[flesh derection](crd_1 .. card_2) tab2

            exemple (cars and  her wheels)

            1 : relationship : Aggregation (wheels is a part of cars + wheels can exist without cars)
            2 : flesh derection :  cars <- wheels (cars is the owner of wheels)
            3 : cardinality :   cars (1..1) <- wheels (4..4)

            => design : cars (1..1) <------------------{Aggregation}------------------(4..4) wheels


            exemple ( post and  her comments)

            1 : relationship : Composition (comments is a part of post + comments can't exist without post)
            2 : flesh derection :  post <- comments (post is the owner of comments)
            3 : cardinality :   post (1..1) <- comments (0..n)

            => design : post (1..1) <------------------{Composition}------------------(0..n) comments


            ===========================================================================================================

            !!! 1: pcontroller and pmethode is static data alredy exist in database can't be deleted or updated
            !!! 2: user is dynamic data can be deleted or updated or created
            !!! 3: permissions can created automatically when user created and deleted automatically when user deleted

            (1) : user and permissions

            1 : relationship : composition (permissions is a part of user + permissions can't exist without user)
            2 : flesh derection :  user <- permissions (user is the owner of permissions)
            3 : cardinality :   user (1..1) <- permissions (0..n)

            => design : user (1..1) <------------------{composition}------------------(0..n) permissions

            (2) : permissions and pcontroller

            1 : relationship : aggregation (pcontroller is a part of permissions + pcontroller can exist without permissions)
            2 : flesh derection :  permissions <- pcontroller (permissions is the owner of pcontroller)
            3 : cardinality :   permissions (1..1) <- pcontroller (1..n)

            => design : permissions (1..1) <------------------{aggregation}------------------(1..n) pcontroller

            (3) : permissions and pmethode

            1 : relationship : aggregation (pmethode is a part of permissions + pmethode can exist without permissions)
            2 : flesh derection :  permissions <- pmethode (permissions is the owner of pmethode)
            3 : cardinality :   permissions (1..1) <- pmethode (1..n)

            => design : permissions (1..1) <------------------{aggregation}------------------(1..n) pmethode

            (4) menu and menu_meal

            1 : relationship : association (menu_meal is a pivot table between menu and meal and relationship is many to many)
            2 : flesh derection :  menu <- menu_meal -> meal (menu and meal are the owner of menu_meal)
            3 : cardinality :   menu (1..n) <- menu_meal (1..n) -> meal (1..n) 
















            */




        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
