<?php

namespace Inventory\Admin\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Inventory\Admin\Traits\InventoryTrait;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\View;
use Input;
use Validator;
use Inventory\Admin\Models\Inventory;
use Inventory\Admin\Models\InventoryMoveLocationKit;
use Inventory\Admin\Models\Category;
use Inventory\Admin\Models\Kits;
use Inventory\Admin\Models\KitStockMap;
use Inventory\Admin\Models\InventoryBulkTransaction;
use Inventory\Admin\Models\InventoryTransactionState;
use Inventory\Admin\Models\InventoryStock;
use Inventory\Admin\Models\InventoryTransaction;
use Inventory\Admin\Models\Location;
use Inventory\Admin\Models\InventoryMoveLocation;
use Datatables;
use Auth;
use Paginate;
use Menu;
use DB;
use Response;
use App\Helpers\Helper as Helper;
use Grids;
use HTML;
use Form;
use Nayjest\Grids\Components\Base\RenderableRegistry;
use Nayjest\Grids\Components\ColumnHeadersRow;
use Nayjest\Grids\Components\ColumnsHider;
use Nayjest\Grids\Components\CsvExport;
use Nayjest\Grids\Components\ExcelExport;
use Nayjest\Grids\Components\Filters\DateRangePicker;
use Nayjest\Grids\Components\FiltersRow;
use Nayjest\Grids\Components\HtmlTag;
use Nayjest\Grids\Components\Laravel5\Pager;
use Nayjest\Grids\Components\OneCellRow;
use Nayjest\Grids\Components\RecordsPerPage;
use Nayjest\Grids\Components\RenderFunc;
use Nayjest\Grids\Components\ShowingRecords;
use Nayjest\Grids\Components\TFoot;
use Nayjest\Grids\Components\THead;
use Nayjest\Grids\Components\TotalsRow;
use Nayjest\Grids\DbalDataProvider;
use Nayjest\Grids\EloquentDataProvider;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\FilterConfig;
use Nayjest\Grids\Grid;
use Nayjest\Grids\GridConfig;
use App\Http\Controllers\Controller;

class InventoryTransactionsController extends Controller {

    public function __construct() {
        $this->middleware( 'auth' );
        parent::__construct();
    }

    public function download_pdf( $bulk_id ) {
        $file = storage_path() . "/transactions/" . $bulk_id . ".pdf";
        $headers = array(
            'Content-Type: application/pdf' ,
        );
        return Response::download( $file , $bulk_id . '.pdf' , $headers );
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index() {
        $page_title = 'Transactions';
        $page_action_title = 'Transactions';
        /* $query = (new InventoryBulkTransaction )
          ->newQuery()
          ->groupBy( 'bulk_id' );
         */
//        if ( Input::get( 'trans_type' ) == 'location' ) {
//            $query = (new InventoryMoveLocation() )
//                    ->newQuery()
//                    ->groupBy( 'bulk_id' );
//        }

        $query = (new InventoryMoveLocation() )
                        ->newQuery()
                        ->groupBy( 'bulk_id' )->orderBy( 'id' , 'DESC' );

        $grid = $this->generateGrid( $page_action_title , ['updated_at' => false , 'created_at' => true ] );
        $grid->setDataProvider(
                new EloquentDataProvider( $query )
        );
        $grid->setColumns( [
                    (new FieldConfig )
                    ->setName( 'bulk_id' )
                    ->setLabel( 'Transaction ID' )
                    ->setCallback( function ($val) {
                        return $val;
                    } )
                    ->setSortable( true )
                    ->addFilter(
                            (new FilterConfig )
                            ->setOperator( FilterConfig::OPERATOR_LIKE )
                    ) ,
                    (new FieldConfig )
                    ->setName( 'created_at' )
                    ->setLabel( 'Date' )
                    ->setSortable( false )
            ,
                    (new FieldConfig )
                    ->setName( 'actions' )
                    ->setLabel( 'Actions' )
                    ->setCallback( function ($val , $row ) {
                        $attr = $row->getSrc();
                        $html = '<a class="btn-primary btn btn-xs" href="' . route( 'transactions.download_pdf' , $attr->bulk_id ) . '" ><span class="fa fa-download"></span> Download PDF</a>';
                        return $html;
                    } )
        ] );
        $grid = new Grid( $grid );
        $grid = $grid->render();
        return $this->view( 'packages::transactions.index' , compact( 'grid' , 'page_title' ) );
    }

    public function create() {

        $i = 0;
        $results = [ ];
        $stored_id = [ ];
        $stock_location_id = Input::get( 'stock_location_id' ); // location
        $stocks_id = Input::get( 'stock_id' );
        $location_lists = Location::where( 'id' , '!=' , $stock_location_id[ 0 ] )->lists( 'name' , 'id' )->all(); // location list
        $inventory_list = InventoryStock::with( 'inventoryName' )->where( 'location_id' , $stock_location_id[ 0 ] )->where( 'deleted_at' , NULL )->groupBy( 'inventory_id' )->get()->toArray();

        foreach ( $inventory_list as $key => $value ) {
            $inventory_lists[ $value[ 'inventory_name' ][ 'id' ] ] = $value[ 'inventory_name' ][ 'name' ];
        }

        $transaction = new InventoryMoveLocation;
        $stocks_quantity = Input::get( 'stock_quantity' );
        $stocks_serial = Input::get( 'stock_serial' );
        $reason = Input::get( 'reason' );
        $location = Location::find( $stock_location_id[ 0 ] );
        $page_title = 'Move stock from: ' . ucfirst( $location[ 'name' ] );

        foreach ( $stocks_id as $stock_id ):
            if ( !in_array( $stocks_id[ $i ] , $stored_id ) ):
                $stored_id[] = $stocks_id[ $i ];
                $stock = InventoryStock::find( $stocks_id[ $i ] );
                $inventoryList = InventoryStock::with( 'inventoryName' )->where( 'id' , $stocks_id[ $i ] )->get();
                if ( $stocks_quantity[ $i ] > $stock->quantity ):
                    $stocks_quantity[ $i ] = $stock->quantity;
                endif;
                $results[ $i ] = ['reason' => $reason[ $i ] , 'stock_id' => $stock->id , 'inventory_id' => $stock->inventory->id , 'inventory_name' => $stock->inventory->name , 'stock_location_id' => $stock->location_id , 'max_stock_quantity' => $stock->quantity , 'stock_quantity' => $stocks_quantity[ $i ] , 'stock_serial' => $stock->serial_no ];
                $assembly_parts = KitStockMap::where( 'kit_stock_id' , '=' , $stock->id )->lists( 'stock_id' )->all(); // location list
                if ( count( $stock->stock ) > 0 ):
                    foreach ( $stock->stock as $part ):
                        $stock_kit = InventoryStock::find( $part->stock_id );
                        $i++;
                        $results[ $i ] = ['reason' => $reason[ $i ] , 'stock_id' => $stock_kit->id , 'inventory_name' => $stock_kit->inventory->name , 'stock_location_id' => $stock_kit->location_id , 'stock_quantity' => $stocks_quantity[ $i ] , 'stock_serial' => $stock_kit->serial_no ];
                    endforeach;
                endif;
                $i++;
            endif;
        endforeach;
        return view( 'packages::transactions.move_location' , compact( 'location_lists' , 'transaction' , 'inventory_lists' , 'results' , 'page_title' ) );
    }

    public function store() {

        $stock_qty = Input::get( 'stock_quantity' );
        $location = Input::get( 'location_id' );
        $comment = Input::get( 'reason' );
        $inventory_id = Input::get( 'stock_inventory_id' );
        $bulk_id = time() . '-' . rand( 1 , 999 );
        $stocks = Input::get( 'stock_id' );

        $kit_records = Inventory::where( 'id' , $inventory_id )->where( 'is_assembly' , 1 )->get();

        if ( count( $kit_records ) > 0 ) {

            $moveLocObj = new InventoryMoveLocation;
            $moveLocObj->bulk_id = $bulk_id;
            $moveLocObj->stock_id = $stocks[ 0 ];
            $moveLocObj->user_id = Auth::user()->id;
            $moveLocObj->old_location = $location[ 0 ];
            $moveLocObj->new_location = Input::get( 'new_location' );
            $moveLocObj->no_of_boxes = Input::get( 'no_of_boxes' );
            $moveLocObj->weight_of_shipment = Input::get( 'new_location' );
            $moveLocObj->comment = Input::get( 'name' );
            //dd( $moveLocObj );
            //$move_location_id = $moveLocObj->id;
            foreach ( $stocks as $key => $value ) {

                InventoryMoveLocationKit::create( ['comment' => 'test' ] );
                $inventoryMoveLocObj = new InventoryMoveLocationKit;
                $inventoryMoveLocObj->move_location_id = "1"; //$move_location_id;
                $inventoryMoveLocObj->parent_id = "0";
                $inventoryMoveLocObj->inventory_id = 2; //$value;
                $inventoryMoveLocObj->quantity = 4; //$stock_qty[ $key ];
                $inventoryMoveLocObj->comment = 'efef'; //$comment[ $key ];
                dd( $inventoryMoveLocObj->save() );


                die( 'uytu' );
            }
            // $kit_stocks = InventoryStock::where( 'inventory_id' , $inventory_id )->where( 'location_id' , $location[ 0 ] )->get();
        }
        // die( 'sss' );




        $toLocation = Location::find( Input::get( 'new_location' ) );
        $results = [ ];
        $reason = Input::get( 'reason' );
        $i = 0;
        if ( count( $location ) > 0 ) {



            foreach ( $stocks as $key => $stock ) {

                $stock = InventoryStock::find( $stock );
                $inventory = $stock->inventory;
                $bulk = new InventoryMoveLocation;
                $bulk->bulk_id = $bulk_id;
                $bulk->old_location = $location[ $key ];
                $bulk->new_location = Input::get( 'new_location' );
                $bulk->stock_id = $stock->id;
                $bulk->observaciones = Input::get( 'name' );
                $bulk->no_of_boxes = Input::get( 'no_of_boxes' );
                $bulk->weight_of_shipment = Input::get( 'weight_of_shipment' );
                $bulk->comment = ''; // $reason[ $key ];
                $bulk->comment_all = Input::get( 'name' );
                $bulk->user_id = Auth::user()->id;
                $bulk->save();
                if ( $stock->inventory->is_serialno == 1 || $stock->inventory->is_assembly == 1 ):
                    $stock->location_id = $toLocation->id;
                    $stock->save();
                else:
                    $destroy_stock = false;
                    if ( $stock_qty[ $key ] == $stock->quantity ):
                        $destroy_stock = true;
                    endif;
                    $stock->take( $stock_qty[ $key ] , $bulk_id );
                    try {
                        $stock->inventory->createStockOnLocation( $stock_qty[ $key ] , $toLocation , $bulk_id , 0 , NULL , NULL , NULL );
                    }
                    catch ( \Inventory\Admin\Exceptions\StockAlreadyExistsException $ex ) {
                        $stock->inventory->putToLocation( $stock_qty[ $key ] , $toLocation , $bulk_id , 0 );
                    }
                    if ( $destroy_stock ):
                        InventoryStock::destroy( $stock->id );
                    endif;
                endif;
                $movement = $stock->movements->last();
                //dd( $movement );
                $results[ $i ][ 'stock_id' ] = $stock->id;
                $results[ $i ][ 'stock_quantity' ] = $stock->quantity;
                $results[ $i ][ 'stock_serial' ] = $stock->serial_no;
                $results[ $i ][ 'part_number' ] = $inventory[ 'part_number' ];
                $results[ $i ][ 'inventory_name' ] = $inventory[ 'name' ];
                $results[ $i ][ 'no_of_boxes' ] = Input::get( 'no_of_boxes' );
                $results[ $i ][ 'observaciones' ] = Input::get( 'name' );
                $results[ $i ][ 'weight_of_shipment' ] = Input::get( 'weight_of_shipment' );
                //$results[ $i ][ 'comment' ] = $reason[ $key ];

                $assembly_parts = KitStockMap::where( 'kit_stock_id' , '=' , $stock->id )->lists( 'stock_id' )->all(); // location list

                $quantity = $stock_qty[ $key ];
                $fromLocation = Location::find( $location[ $key ] );
                //$from_reason = $reason[ $key ];
                // $reason = Input::get( 'name' );
                // dd( $inventory->takeFromLocation( $quantity , $fromLocation , $from_reason = '' ) );
                /*
                  try {
                  $inventory->putToLocation( $quantity , $toLocation , $reason = '' , $cost = 0 );
                  }
                  catch ( StockNotFoundException $ex ) {
                  $inventory->createStockOnLocation( $quantity , $toLocation , $reason , $cost = 0 , $aisle = NULL , $row = NULL , $bin = NULL );
                  }


                  /* if ( count( $stock->stock ) > 0 ):

                  foreach ( $stock->stock as $part ):

                  $stock_kit = InventoryStock::find( $part->stock_id );
                  $i++;
                  $bulk = new InventoryMoveLocation;
                  $bulk->bulk_id = $bulk_id;
                  $bulk->old_location = $location[ $key ];
                  $bulk->new_location = Input::get( 'new_location' );
                  $bulk->stock_id = $stock_kit->id;
                  $bulk->observaciones = Input::get( 'name' );
                  $bulk->no_of_boxes = Input::get( 'no_of_boxes' );
                  $bulk->weight_of_shipment = Input::get( 'weight_of_shipment' );
                  $bulk->comment = $reason[ $key ];
                  $bulk->quantity = $stock_qty[ $key ];
                  $bulk->comment_all = Input::get( 'name' );
                  $bulk->user_id = Auth::user()->id;
                  $bulk->save();

                  $stock->moveTo( $toLocation );
                  /*
                  $quantity = $stock_qty[ $key ];

                  $from_location = Location::find( $location[ $key ] );
                  $from_reason = $reason[ $key ];
                  $location = Location::find( Input::get( 'new_location' ) );
                  $reason = Input::get( 'name' );

                  $stock->takeFromLocation( $quantity , $from_location , $from_reason = '' );

                  $stock->putToLocation( $quantity , $toLocation , $reason = '' , $cost = 0 );

                  $stock->take( $quantity , $from_reason );
                  $stock->remove( $quantity , $reason );

                  $stock->add( $quantity , $from_reason , $cost = 0 );
                  $stock->put( $quantity , $reason , $cost = 0 );

                  $stock->createStockOnLocation( $quantity , $location , $reason , $cost = 0 , $aisle = NULL , $row = NULL , $bin = NULL );


                  endforeach;

                  endif; */
                $i++;
            }


            $fromLocation = Location::find( $location[ 0 ] );

            //dd( $fromLocation );
            $route = 'inventory/transactions?trans_type=location';

            $pdf = \PDF::loadView( 'packages::transactions.templates.transaction' , ['fromLocation' => $fromLocation , 'toLocation' => $toLocation , 'results' => $results , 'results' => $results , 'results' => $results , 'bulk_id' => $bulk_id , 'state_description' => 'blabla blbabb abbaab' , 'state' => Input::get( 'state' ) ] )->save( storage_path( 'transactions/' . $bulk_id . '.pdf' ) );
            return Redirect::to( ($route ) )
                            ->with( 'flash_alert_notice' , 'Stock moved' )->with( 'alert_class' , 'alert-success alert-dismissable' );
        }
        else {

            foreach ( $stocks as $stock ):
                $stock = InventoryStock::find( $stock );
                $stock->moveTo( $toLocation );
                $inventory = Inventory::find( $stock->inventory_id );
                $transaction = $stock->newTransaction( Input::get( 'name' ) );
                $transaction->state = Input::get( 'state' );
                $transaction->quantity = $stock->quantity;
                $transaction->save();

                $bulk = new InventoryBulkTransaction;
                $bulk->bulk_id = $bulk_id;
                $bulk->transaction_id = $transaction->id;
                $bulk->save();
                $results[ $i ][ 'stock_id' ] = $stock->id;
                $results[ $i ][ 'stock_quantity' ] = $stock->quantity;
                $results[ $i ][ 'stock_serial' ] = $stock->serial_no;
                $results[ $i ][ 'part_number' ] = $inventory->part_number;
                $results[ $i ][ 'inventory_name' ] = $inventory->name;
                $i++;
            endforeach;
            $route = route( 'transactions' );

            $pdf = \PDF::loadView( 'packages::transactions.templates.transaction' , ['results' => $results , 'bulk_id' => $bulk_id , 'state_description' => 'blabla blbabb abbaab' , 'state' => Input::get( 'state' ) ] )->save( storage_path( 'transactions/' . $bulk_id . '.pdf' ) );
            return Redirect::to( ($route ) )
                            ->with( 'flash_alert_notice' , 'Transaction created' )->with( 'alert_class' , 'alert-success alert-dismissable' );
        }
    }

}
