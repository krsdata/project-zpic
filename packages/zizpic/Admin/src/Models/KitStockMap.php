<?php

namespace Inventory\Admin\Models;

use Validator;
use Input;

class KitStockMap extends BaseModel {

    protected $table = 'kit_stock_map';

    static public function isValidate() {
        $rules = [
        ];

        $validator = Validator::make( Input::all() , $rules );
        return $validator;
    }

    public function inventoryName() {
        return $this->belongsTo( 'Inventory\Admin\Models\Inventory' , 'kit_id' , 'id' );
    }

    public function inventoryStockRelation() {
        return $this->belongsTo( 'Inventory\Admin\Models\InventoryStock' , 'kit_stock_id' , 'id' );
    }

    public function inventoryRelation() {
        return $this->belongsTo( 'Inventory\Admin\Models\Inventory' , 'kit_stock_id' , 'id' );
    }

    public function inventoryStockName() {
        return $this->belongsTo( 'Inventory\Admin\Models\Inventory' , 'Inventory' , 'id' );
    }

}
