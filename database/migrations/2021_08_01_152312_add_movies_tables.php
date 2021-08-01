<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoviesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_groups', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->timestamps();
        });
        Schema::create('movie_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('movie_group_id')->constrained('movie_groups');
            $table->timestamps();
        });
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->foreignId('movie_group_id')->constrained('movie_groups');
            $table->foreignId('added_by')->constrained('users');
            $table->boolean('seen');
            $table->integer('rating')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
        Schema::dropIfExists('movie_group_members');
        Schema::dropIfExists('movie_groups');
    }
}
