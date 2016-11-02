<?php
namespace Inventory\Admin\Models;
use Validator;
use Input;

class Supplier extends BaseModel
{
    
        /**
     * The metrics table.
     *
     * @var string
     */
    protected $table = 'suppliers';
    protected $guarded = ['id' , 'created_at' , 'updated_at' ];
    protected $fillable = ['name' ,'contact_phone', 'contact_fax','contact_email','address','region','postal_code','zip_code','city','country','contact_title','contact_name' ];


    
}