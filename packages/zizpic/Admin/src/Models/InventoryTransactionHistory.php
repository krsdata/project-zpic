<?php

namespace Inventory\Admin\Models;

use Inventory\Admin\Traits\InventoryTransactionHistoryTrait;

class InventoryTransactionHistory extends BaseModel {

    use InventoryTransactionHistoryTrait;

    /**
     * The inventory transaction histories table.
     *
     * @var string
     */
    protected $table = 'inventory_transaction_histories';

    /**
     * The belongsTo transaction relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction() {
        return $this->belongsTo( 'Inventory\Admin\Models\InventoryTransaction' , 'transaction_id' , 'id' );
    }

}
