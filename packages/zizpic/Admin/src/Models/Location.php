<?php

namespace Inventory\Admin\Models;

use Phoenix\EloquentMeta\MetaTrait;
use Illuminate\Database\Eloquent\Model;
use Validator;
use Input;

class Location extends BaseModel {

    use MetaTrait;

    protected $table = 'locations';
    protected $guarded = ['id' , 'created_at' , 'updated_at' ];
    protected $fillable = ['name' , 'parent_id' , 'description' ];

    public function stocks() {
        return $this->hasMany( 'Inventory\Admin\Models\InventoryStock' , 'location_id' , 'id' );
    }

}
