<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKitInventoryMapTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kit_inventory_map', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('kit_id')->unsigned();
			
			$table->integer('created_by')->unsigned();
			$table->foreign('created_by')->references('id')->on('users');

			$table->integer('inventory_id')->unsigned();
			$table->foreign('inventory_id')->references('id')->on('inventories');

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
		Schema::dropIfExists('kit_inventory_map');
	}

}
