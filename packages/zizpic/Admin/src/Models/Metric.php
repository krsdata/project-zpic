<?php

namespace Inventory\Admin\Models;

class Metric extends BaseModel {

    /**
     * The metrics table.
     *
     * @var string
     */
    protected $table = 'metrics';
    protected $guarded = ['created_at' , 'updated_at' , 'user_id' ];
    protected $fillable = ['name' , 'symbol' ];

    /**
     * The hasMany inventory items relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items() {
        return $this->hasMany( 'Inventory\Admin\Models\Inventory' , 'metric_id' , 'id' );
    }

}
