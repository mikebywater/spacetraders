<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->bigInteger('value')->nullable();
            $table->string('good')->nullable();
            $table->string('ship_id')->nullable();
            $table->string('location_id')->nullable();
            $table->bigInteger('total_credits')->nullable();
            $table->bigInteger('total_loan')->nullable();
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
        Schema::dropIfExists('trades');
    }
}
