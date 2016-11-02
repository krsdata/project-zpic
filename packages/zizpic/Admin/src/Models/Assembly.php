<?php

namespace Inventory\Admin\Models;

use Validator;
use Input;

class Assembly extends BaseModel {

    protected $table = 'inventory_assemblies';

    static public function isValidate() {
        $rules = [
            'name' => 'required' ,
        ];

        $validator = Validator::make( Input::all() , $rules );
        return $validator;
    }

    public function inventory() {
        return $this->hasMany( 'Inventory\Admin\Models\Inventory' );
    }

}
