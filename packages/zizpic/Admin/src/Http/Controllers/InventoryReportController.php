<?php

namespace Inventory\Admin\Http\Controllers;

use Inventory\Admin\Models\Inventory;
use Inventory\Admin\Models\InventoryStock;
use Nayjest\Grids\EloquentDataProvider;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\SelectFilterConfig;
use Nayjest\Grids\FilterConfig;
use Nayjest\Grids\Grid;
use App\Http\Controllers\Controller;
use Inventory\Admin\Models\Location;
use Inventory\Admin\Traits\InventoryTransactionTrait;

//use View;

class InventoryReportController extends Controller {

    public function __construct() {
        $this->middleware( 'auth' );
        parent::__construct();
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function inventoryStockByLocation() {
        //  $stock = new InventoryStock;
        //$qty = $stock->hasEnoughStock( $this->getAttribute( 10 ) );
        // dd( $qty );

        $query = (new InventoryStock )
                ->newQuery()
                ->join( 'inventories' , 'inventories.id' , '=' , 'inventory_stocks.inventory_id' )
                ->whereRaw( 'inventory_stocks.id NOT IN (SELECT stock_id FROM kit_stock_map)' )
                ->select( 'inventory_stocks.inventory_id' , 'inventory_stocks.location_id' , \DB::raw( 'sum(inventory_stocks.quantity) as total' ) )
                ->groupBy( 'inventory_stocks.inventory_id' , 'inventory_stocks.location_id' );


        $page_title = 'Inventory Stock Report';
        $page_action_title = 'Inventory Stock Report';
        $locations = Location::lists( 'name' , 'id' )->all();
        $inventories = Inventory::lists( 'name' , 'id' )->all();

        $grid = $this->generateGrid( $page_action_title , ['updated_at' => false ] );
        $grid->setDataProvider(
                new EloquentDataProvider( $query )
        );
        $grid->setColumns( [
                    (new FieldConfig )
                    ->setName( 'location_id' )
                    ->setLabel( 'Location' )
                    ->setCallback( function ($val) {
                        $locations = Location::lists( 'name' , 'id' );
                        return $locations[ $val ];
                    } )
                    ->setSortable( true )
                    ->addFilter(
                            (new SelectFilterConfig )
                            ->setName( 'location_id' )
                            ->setOptions( $locations )
                    ) ,
                    (new FieldConfig )
                    ->setName( 'inventory_id' )
                    ->setLabel( 'Item Name' )
                    ->setCallback( function ($val) {
                        $inventories = Inventory::lists( 'name' , 'id' );
                        return $inventories[ $val ];
                    } )
                    ->setSortable( true )
                    ->addFilter(
                            (new SelectFilterConfig )
                            ->setName( 'inventory_id' )
                            ->setOptions( $inventories )
                    ) ,
                    (new FieldConfig )
                    ->setName( 'part_number' )
                    ->setLabel( 'Part Number' )
                    ->setCallback( function ($val) {
                        return $val;
                    } )
                    ->setSortable( true )
                    ->addFilter(
                            (new FilterConfig )
                            ->setOperator( FilterConfig::OPERATOR_LIKE )
                    ) ,
                    (new FieldConfig )
                    ->setName( 'total' )
                    ->setLabel( 'Quantity' )
                    ->setCallback( function ($val , $row) {
                        return $val;
                    } )
                    ->setSortable( true )
        ] );
        $grid = new Grid( $grid );
        $grid = $grid->render();
        return $this->view( 'packages::reports.index' , compact( 'grid' , 'page_action_title' , 'page_title' ) );
    }

}
