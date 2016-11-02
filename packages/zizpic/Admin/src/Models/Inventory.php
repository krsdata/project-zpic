<?php

namespace Inventory\Admin\Models;

use Inventory\Admin\Traits\AssemblyTrait;
use Inventory\Admin\Traits\InventoryVariantTrait;
use Inventory\Admin\Traits\InventoryTrait;
use Validator;
use Input;

class Inventory extends BaseModel {

    use InventoryTrait;

use InventoryVariantTrait;

use AssemblyTrait;

    protected $table = 'inventories';
    protected $guarded = ['id' , 'created_at' , 'updated_at' , 'user_id' ];
    protected $fillable = ['name' , 'is_serialno' , 'part_number' , 'category_id' , 'metric_id' , 'description' , 'parent_id' , 'is_kit' , 'is_assembly' ];

    /**
     * The hasOne category relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category() {
        return $this->hasOne( 'Inventory\Admin\Models\Category' , 'id' , 'category_id' );
    }

    /**
     * The hasOne metric relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function metric() {
        return $this->hasOne( 'Inventory\Admin\Models\Metric' , 'id' , 'metric_id' );
    }

    /**
     * The hasOne sku relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sku() {
        return $this->hasOne( 'Inventory\Admin\Models\InventorySku' , 'inventory_id' , 'id' );
    }

    /**
     * The hasMany stocks relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stocks() {
        return $this->hasMany( 'Inventory\Admin\Models\InventoryStock' , 'inventory_id' , 'id' );
    }

    /**
     * The belongsToMany suppliers relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function suppliers() {
        return $this->belongsToMany( 'Inventory\Admin\Models\Supplier' , 'inventory_suppliers' , 'inventory_id' )->withTimestamps();
    }

    /**
     * The belongsToMany assemblies relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assemblies() {
        return $this->belongsToMany( $this , 'inventory_assemblies' , 'inventory_id' , 'part_id' )->withPivot( ['quantity' ] )->withTimestamps();
    }

}
