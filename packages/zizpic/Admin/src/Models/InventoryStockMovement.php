<?php

namespace Inventory\Admin\Models;

use Inventory\Admin\Traits\InventoryStockTrait;
use Inventory\Admin\Traits\InventoryStockMovementTrait;

class InventoryStockMovement extends BaseModel {

    use InventoryStockMovementTrait;

    /**
     * The inventory stock movements table.
     *
     * @var string
     */
    protected $table = 'inventory_stock_movements';

    /**
     * The belongsTo stock relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stock() {
        return $this->belongsTo( 'Inventory\Admin\Models\InventoryStock' , 'stock_id' , 'id' );
    }

}
