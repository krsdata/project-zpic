<?php

namespace Inventory\Admin\Models;

class InventoryMoveLocationKit extends BaseModel {

    /**
     * The metrics table.
     *
     * @var string
     */
    protected $table = 'inventory_move_location_item';
    protected $guarded = ['id' , 'created_at' , 'updated_at' ];
    protected $fillable = array(
        'inventory_id' ,
        'code' ,
    );

    /**
     * The hasMany inventory items relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
}
