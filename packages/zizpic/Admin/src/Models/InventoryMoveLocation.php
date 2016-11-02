<?php

namespace Inventory\Admin\Models;

class InventoryMoveLocation extends BaseModel {

    /**
     * The metrics table.
     *
     * @var string
     */
    protected $table = 'inventory_move_location';
    protected $guarded = ['id' , 'created_at' , 'updated_at' ];
    protected $fillable = ['state' , 'description' , 'bulk_id' , 'stock_id' , 'old_location' , 'new_location' , 'observaciones' , 'no_of_boxes' , 'weight_of_shipment' , 'comment' , 'comment_all' ];

    /**
     * The hasMany inventory items relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
}
