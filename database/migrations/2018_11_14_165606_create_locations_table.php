<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('map_id');
            $table->string('name');
            $table->text('description');
            $table->integer('position')->nullable();
            $table->string('lat');
            $table->string('lon');
            $table->string('image')->default("");
            $table->timestamps();

            $table->unique(['map_id', 'position']);
            $table->foreign('map_id')->references('id')->on('maps')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
