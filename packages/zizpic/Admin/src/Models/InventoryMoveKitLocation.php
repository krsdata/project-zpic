<?php

namespace Inventory\Admin\Models;

class InventoryMoveKitLocation extends BaseModel {

    /**
     * The metrics table.
     *
     * @var string
     */
    protected $table = 'inventory_move_location_items';
    protected $guarded = ['id' ];
    protected $fillable = ['move_location_id' , 'parent_id' , 'inventory_id' , 'quantity' , 'comment' ];

    /**
     * The hasMany inventory items relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
}
