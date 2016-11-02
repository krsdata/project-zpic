<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryKitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_kits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('kit_id')->unsigned();
            $table->foreign('kit_id')->references('id')->on('inventories');
            $table->integer('stock_id')->unsigned();
            $table->foreign('stock_id')->references('id')->on('inventory_stocks');
            $table->integer('quantity');
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
        Schema::drop('inventory_kits');
    }
}
