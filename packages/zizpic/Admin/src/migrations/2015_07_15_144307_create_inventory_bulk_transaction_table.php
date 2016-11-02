<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryBulkTransactionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inventory_bulk_transaction', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->string('bulk_id');
			$table->string('bulk_comment');
			$table->integer('transaction_id')->unsigned();
			$table->foreign('transaction_id')->references('id')->on('inventory_transactions');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('inventory_bulk_transaction');
	}

}
