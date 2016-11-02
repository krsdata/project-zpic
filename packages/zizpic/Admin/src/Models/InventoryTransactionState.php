<?php

namespace Inventory\Admin\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryTransactionState extends BaseModel {

    /**
     * The metrics table.
     *
     * @var string
     */
    use SoftDeletes;

    protected $dates = ['deleted_at' ];
    protected $table = 'inventory_transaction_state';
    protected $guarded = ['id' , 'created_at' , 'updated_at' ];
    protected $fillable = ['state' , 'description' ];

    /**
     * The hasMany inventory items relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
}
