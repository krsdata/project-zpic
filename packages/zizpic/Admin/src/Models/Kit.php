<?php

namespace Inventory\Admin\Models;

use Validator;
use Input;

class Kit extends BaseModel {

    protected $table = 'inventory_kits';

    public function stock() {
        return $this->hasMany( 'Inventory\Admin\Models\InventoryStock' );
    }

}
