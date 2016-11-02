<?php

namespace Inventory\Admin\Models;

use Validator;
use Input;

class InventoryBulkTransaction extends BaseModel {

    /**
     * The inventory stocks table.
     *
     * @var string
     */
    protected $table = 'inventory_bulk_transaction';
    protected $guarded = ['id' , 'created_at' , 'updated_at' ];

    /**
     * The belongsTo inventory item relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
}
