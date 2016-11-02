<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKitStockMapTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kit_stock_map', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('kit_stock_id')->unsigned();
			
			$table->integer('created_by')->unsigned();
			$table->foreign('created_by')->references('id')->on('users');

			$table->integer('stock_id')->unsigned();
			$table->foreign('stock_id')->references('id')->on('inventory_stocks');
			$table->string('serial_no');
			$table->integer('quantity');
			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('kit_stock_map');
	}

}
