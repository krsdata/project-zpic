<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryMoveLocationTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'inventory_move_location' , function(Blueprint $table) {
            $table->increments( 'id' );
            $table->timestamps();
            $table->string( 'bulk_id' );
            $table->integer( 'stock_id' )->unsigned();
            $table->integer( 'user_id' )->unsigned()->nullable();
            $table->integer( 'old_location' );
            $table->integer( 'new_location' );
            $table->string( 'observaciones' );
            $table->string( 'no_of_boxes' );
            $table->string( 'weight_of_shipment' );
            $table->text( 'comment' );
            $table->text( 'comment_all' );
            $table->foreign( 'stock_id' )->references( 'id' )->on( 'inventory_stocks' )
                    ->onUpdate( 'restrict' )
                    ->onDelete( 'cascade' );
            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' )
                    ->onUpdate( 'restrict' )
                    ->onDelete( 'set null' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'inventory_move_location' );
    }

}
