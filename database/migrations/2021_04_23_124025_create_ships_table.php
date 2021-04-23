<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ships', function (Blueprint $table) {
            $table->string("id")->primary();
            $table->string("class")->nullable();
            $table->string("location")->nullable();
            $table-> string ("manufacturer")->nullable();
            $table->biginteger ("maxCargo")->nullable();
            $table->biginteger("plating")->nullable();
            $table->biginteger("spaceAvailable")->nullable();
            $table->biginteger("speed")->nullable();
            $table->string("type")->nullable();
            $table->biginteger("weapons")->nullable();
            $table->float("x")->nullable();
            $table->float ("y")->nullable();
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
        Schema::dropIfExists('ships');
    }
}
