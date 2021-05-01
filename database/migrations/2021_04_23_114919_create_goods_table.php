<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET SESSION sql_require_primary_key=0');
        Schema::create('goods', function (Blueprint $table) {
            $table->string ("id")->primary();
            $table->biginteger("quantityAvailable");
            $table->biginteger("volumePerUnit");
            $table->biginteger("pricePerUnit");
            $table->bigInteger('purchasePricePerUnit');
            $table->bigInteger('sellPricePerUnit');
            $table->string('type')->nullable();
            $table->string('location')->nullable();
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
        Schema::dropIfExists('goods');
    }
}
