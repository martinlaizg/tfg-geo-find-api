<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Map extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->default('Sin descripcion');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('image');
            $table->unsignedInteger('creator_id');
            $table->enum('min_level', ['therm', 'compass', 'map']);
            $table->timestamps();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maps');
    }
}
