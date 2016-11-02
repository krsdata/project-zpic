<?php

namespace Inventory\Admin\Models;

use Inventory\Admin\Traits\InventoryTransactionTrait;
use Inventory\Admin\Interfaces\StateableInterface;

class InventoryTransaction extends BaseModel implements StateableInterface {

    use InventoryTransactionTrait;

    /**
     * The inventory transactions table.
     *
     * @var string
     */
    protected $table = 'inventory_transactions';

    /**
     * The belongsTo stock relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stock() {
        return $this->belongsTo( 'Inventory\Admin\Models\InventoryStock' , 'stock_id' , 'id' );
    }

    /**
     * The hasMany histories relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function histories() {
        return $this->hasMany( 'Inventory\Admin\Models\InventoryTransactionHistory' , 'transaction_id' , 'id' );
    }

}
