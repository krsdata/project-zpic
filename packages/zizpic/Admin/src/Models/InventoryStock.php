<?php

namespace Inventory\Admin\Models;

use Inventory\Admin\Traits\InventoryStockTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Inventory\Admin\Traits\InventoryTrait;
use Validator;
use Input;

class InventoryStock extends BaseModel {

    use InventoryStockTrait ,
        SoftDeletes;

    /**
     * The inventory stocks table.
     *
     * @var string
     */
    protected $table = 'inventory_stocks';
    protected $guarded = ['id' , 'created_at' , 'updated_at' , 'user_id' , 'deleted_at' ];
    protected $fillable = ['name' , 'inventory_id' , 'location_id' , 'quantity' , 'serial_no' , 'aisle' , 'row' , 'bin' ];
    protected $dates = ['deleted_at' ];

    /**
     * The belongsTo inventory item relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventoryName() {
        return $this->belongsTo( 'Inventory\Admin\Models\Inventory' , 'inventory_id' , 'id' );
    }

    public function inventory() {
        return $this->belongsTo( 'Inventory\Admin\Models\Inventory' , 'inventory_id' , 'id' );
    }

    public function item() {
        return $this->belongsTo( 'Inventory\Admin\Models\\Inventory' , 'inventory_id' , 'id' );
    }

    /**
     * The hasMany movements relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movements() {
        return $this->hasMany( 'Inventory\Admin\Models\InventoryStockMovement' , 'stock_id' , 'id' );
    }

    public function stock() {
        return $this->hasMany( 'Inventory\Admin\Models\KitStockMap' , 'kit_stock_id' , 'id' );
    }

    /**
     * The hasMany transactions relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions() {
        return $this->hasMany( 'Inventory\Admin\Models\InventoryTransaction' , 'stock_id' , 'id' );
    }

    public function location() {
        return $this->hasOne( 'Inventory\Admin\Models\Location' , 'id' , 'location_id' );
    }

    /**
     * The hasOne location relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function locationName() {
        return $this->belongsTo( 'Inventory\Admin\Models\Location' , 'location_id' , 'id' );
    }

    public function KitStockRelation() {

        return $this->hasMany( 'Inventory\Admin\Models\KitStockMap' , 'kit_stock_id' );
    }

    /* public function kit() {

      return $this->hasMany( 'Inventory\Admin\Models\KitStockMap' , 'stock_id' );
      } */

    public function kitStockMapRelation() {
        return $this->hasMany( 'Inventory\Admin\Models\KitStockMap' , 'stock_id' , 'id' );
    }

    public function kit() {
        return $this->belongsTo( 'Inventory\Admin\Models\KitStockMap' , 'kit_stock_id' );
    }

    public function kit_stocks() {
        return $this->hasMany( 'Inventory\Admin\Models\KitStockMap' , 'stock_id' );
    }

    public function kits() {
        return $this->belongsToMany( $this , 'inventory_kits' , 'kit_id' , 'stock_id' )->withPivot( ['quantity' ] )->withTimestamps();
    }

}
