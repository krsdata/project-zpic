<?php

namespace Inventory\Admin\Models;

use Inventory\Admin\Traits\InventorySkuTrait;

class InventorySku extends BaseModel {

    use InventorySkuTrait;

    protected $table = 'inventory_skus';
    protected $fillable = array(
        'inventory_id' ,
        'code' ,
    );

    public function item() {
        return $this->belongsTo( 'Inventory' , 'inventory_id' , 'id' );
    }

}
