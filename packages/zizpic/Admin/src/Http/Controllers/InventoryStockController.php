<?php

namespace Inventory\Admin\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Input;
use Inventory\Admin\Models\InventoryStock;
use Inventory\Admin\Http\Requests\StocksRequest;
use Inventory\Admin\Models\Location;
use Inventory\Admin\Models\Inventory;
use Inventory\Admin\Models\InventoryTransactionState;
use App\Helpers\Helper as Helper;
use Auth;
use App\Http\Controllers\Controller;
use Session;
use DB;
use Nayjest\Grids\EloquentDataProvider;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\SelectFilterConfig;
use Nayjest\Grids\FilterConfig;
use Nayjest\Grids\Grid;
use Form;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Request;
use Inventory\Admin\Models\Kit;

/**
 * Class SupplierController
 */
class InventoryStockController extends Controller {

    public function __construct() {
        $this->middleware( 'auth' );
        parent::__construct();
    }

    public function get_stocks_for_transaction( $inventory ) {

        $stocks = DB::table( 'inventory_stocks' )
                //->rightJoin( 'kit_stock_map' , 'inventory_stocks.id' , '<>' , 'kit_stock_map.stock_id' )
                ->whereRaw( 'inventory_stocks.id NOT IN (SELECT stock_id FROM kit_stock_map)' )
                ->select( 'inventory_stocks.id' , 'inventory_stocks.quantity' , 'inventory_stocks.serial_no' )
                ->where( 'inventory_id' , $inventory )
                ->get();
        $result = [ ];


        foreach ( $stocks as $stock ):
            $result[] = [$stock->id , $stock->quantity , $stock->serial_no , "<button data-stock-quantity='$stock->quantity' data-stock-serial_no='$stock->serial_no' data-stock-id='$stock->id' class='preventSubmit addStockRecord fa fa-plus'></button>" ];
        endforeach;
        return array( 'data' => $result );
    }

    public function get_stocks_for_transaction_by_location( $inventory ) {



        $stocks = DB::table( 'inventory_stocks' )
                //->rightJoin( 'kit_stock_map' , 'inventory_stocks.id' , '<>' , 'kit_stock_map.stock_id' )
                ->whereRaw( 'inventory_stocks.id NOT IN (SELECT stock_id FROM kit_stock_map)' )
                ->select( 'inventory_stocks.id' , 'inventory_stocks.location_id' , 'inventory_stocks.quantity' , 'inventory_stocks.serial_no' )
                ->where( 'inventory_id' , $inventory )->where( 'deleted_at' , null )
                ->get();


        $result = [ ];
        foreach ( $stocks as $stock ):
            $result[] = [$stock->id , $stock->quantity , $stock->serial_no , "<button data-stock-quantity='$stock->quantity' data-stock-serial_no='$stock->serial_no' data-stock-id='$stock->id' data-stock-location_id='$stock->location_id' class='preventSubmit addStockRecord fa fa-plus'></button>" ];
        endforeach;
        return array( 'data' => $result );
    }

    /**
     * Displays all metrics.
     *
     * @return \Illuminate\View\View
     */
    public function index( Inventory $inventory , InventoryStock $inventoryStock ) {
        $state_lists = InventoryTransactionState::lists( 'state' , 'state' );
        $helper = new Helper();
        $page_action_title = $inventory->name . ' - Units';
        $page_title = $inventory->name . ' - Stock Management';
        $results = $inventory->stocks;

        if ( $results->count() == 0 ) {
            return Redirect::to( 'inventory/inventories' )
                            ->with( 'flash_alert_notice' , 'No stocks associated' )->with( 'alert_class' , 'alert-info alert-dismissable' );
        }
        $grid = $this->generateGrid( $page_action_title , ['updated_at' => false ] );
        $grid->setDataProvider(
                new EloquentDataProvider( InventoryStock::query()->where( 'inventory_id' , $inventory->id )->where( 'quantity' , '>' , 0 ) )
        );
        $grid->setColumns( [
                    (new FieldConfig )
                    ->setName( 'location_id' )
                    ->setLabel( 'Location' )
                    ->setCallback( function ($val , $row) {
                                $attr = $row->getSrc();
                                return $attr->location->name;
                            } )
                    ->setSortable( true )
                    ->addFilter(
                            (new FilterConfig )
                            ->setOperator( FilterConfig::OPERATOR_LIKE )
                    ) ,
                    (new FieldConfig )
                    ->setName( 'serial_no' )
                    ->setLabel( 'S/N' )
                    ->setCallback( function ($val) {
                                if ( $val != NULL )
                                    return $val;
                                return 'N/A';
                            } )
                    ->setSortable( true )
                    ->addFilter(
                            (new FilterConfig )
                            ->setOperator( FilterConfig::OPERATOR_LIKE )
                    ) ,
                    (new FieldConfig )
                    ->setName( 'aisle' )
                    ->setLabel( 'Aisle' )
                    ->setCallback( function ($val) {
                                return $val;
                            } )
                    ->setSortable( true )
                    ->addFilter(
                            (new FilterConfig )
                            ->setOperator( FilterConfig::OPERATOR_LIKE )
                    ) ,
                    (new FieldConfig )
                    ->setName( 'row' )
                    ->setLabel( 'Row' )
                    ->setCallback( function ($val) {
                                return $val;
                            } )
                    ->setSortable( true )
                    ->addFilter(
                            (new FilterConfig )
                            ->setOperator( FilterConfig::OPERATOR_LIKE )
                    ) ,
                    (new FieldConfig )
                    ->setName( 'bin' )
                    ->setLabel( 'Bin' )
                    ->setCallback( function ($val) {
                                return $val;
                            } )
                    ->setSortable( true )
                    ->addFilter(
                            (new FilterConfig )
                            ->setOperator( FilterConfig::OPERATOR_LIKE )
                    ) ,
                    (new FieldConfig )
                    ->setName( 'quantity' )
                    ->setLabel( 'Quantity' )
                    ->setCallback( function ($val) {
                                return $val;
                            } )
                    ->setSortable( true )
                    ->addFilter(
                            (new FilterConfig )
                            ->setOperator( FilterConfig::OPERATOR_LIKE )
                    ) ,
                    (new FieldConfig )
                    ->setName( 'actions' )
                    ->setLabel( 'Actions' )
                    ->setCallback( function ($val , $row ) {
                                $attr = $row->getSrc();
                                $html = '';
                                if ( count( $attr->kit ) > 0 ):
                                    $html = 'Stock attached to kit.';
                                else:
                                    if ( $attr->inventory->is_assembly == 1 ):
                                        $html .= '<a href="' . url( 'inventory/kits/edit/' . $attr->id ) . '"><i class=" fa fa-pencil-square-o"></i> </a>';
                                    endif;
                                    $html .= Form::open( array( 'class' => 'form-inline pull-left' , 'method' => 'POST' , 'route' => array( 'transactions.create' ) ) );
                                    $html .= Form::hidden( 'stock_id[]' , $attr->id );
                                    $html .=Form::hidden( 'stock_location_id[]' , $attr->location_id );
                                    $html .=Form::hidden( 'stock_quantity[]' , $attr->quantity );
                                    $html .=Form::hidden( 'stock_serial[]' , $attr->serial_no );
                                    $html .=Form::button( 'Move location' , array( 'class' => 'btn btn-primary btn-xs' , 'type' => 'submit' ) );
                                    $html .=Form::close();
                                endif;


                                return $html;
                            } )
                ] );
                $grid = new Grid( $grid );
                $grid = $grid->render();

                return $this->view( 'packages::inventories.stocks1.index' , compact( 'grid' , 'routes' , 'inventory' , 'state_lists' , 'id' , 'inventoryStock' , 'results' , 'page_action_title' , 'page_title' ) );
            }

            /**
             * Displays the form to create a metric.
             *
             * @return \Illuminate\View\View
             */
            public function create( Inventory $inventory , InventoryStock $inventoryStock ) {
                $page_title = 'Inventory Unit Management';
                $is_assembly = 0;
                if ( ($inventory[ 'is_assembly' ] == 1 ) ) {
                    $is_assembly = 1;
                    $page_action_title = 'Create Kit Unit';
                }
                else {
                    $page_action_title = 'Create Stock Unit - ' . $inventory->name;
                }


                $inventory_id = $inventory->id;
                $serial_num = '';
                if ( $inventory == null ) {
                    return Redirect::to( 'inventory/inventories' );
                }
                else {
                    $serial_num = $inventory->is_serialno;
                }
                $category_lists = Location::lists( 'name' , 'id' )->all();
                $inventory_lists = Inventory::lists( 'name' , 'id' )->all();
                return view( 'packages::inventories.stocks.create' , compact( 'inventory' , 'is_assembly' , 'inventoryStock' , 'serial_num' , 'inventory_id' , 'page_action_title' , 'page_title' , 'category_lists' , 'metric_lists' , 'inventory_lists' ) );
            }

            /**
             * Creates a metric.
             *
             * @param SupplierRequest $request
             *
             * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
             */
            public function split( InventoryStock $inventoryStock ) {

                print_r( Input::all() );
                $id = Input::get( 'inventory_id' );
                $stock_id = Input::get( 'stock_id' );
                $create_trans = Input::get( 'create_trans' );
                $stock_split = isset( $stock_id ) ? InventoryStock::find( Input::get( 'stock_id' ) ) : null;
                $msg = "Inventory stocks was successfully created !";
                if ( $stock_split != null && isset( $stock_id ) ) {
                    $qty = $stock_split->quantity - Input::get( 'quantity' );
                    $stock_split->quantity = $qty;
                    $stock_split->save();
                    $inventoryStock->fill( Input::all() );
                    $inventoryStock->user_id = Auth::user()->id;
                    $inventoryStock->save();
                    $msg = "Inventory stock was successfully splited";
                }


                return Redirect::to( 'inventories/' . $id . '/stocks' )
                                ->with( 'flash_alert_notice' , $msg )->with( 'alert_class' , 'alert-success alert-dismissable' );
            }

            public function store( StocksRequest $request , Inventory $item , InventoryStock $inventoryStock ) {
                if ( $item->is_serialno == 1 || $item->is_assembly == 1 ):
                    $inventoryStock->fill( Input::all() );
                    $inventoryStock->user_id = Auth::user()->id;
                    $inventoryStock->save();
                else:
                    $location = Location::find( Input::get( 'location_id' ) );
                    try {
                        $item->createStockOnLocation( Input::get( 'quantity' ) , $location , 'Add Units' , 0 , NULL , NULL , NULL );
                    }
                    catch ( \Inventory\Admin\Exceptions\StockAlreadyExistsException $ex ) {
                        $item->putToLocation( Input::get( 'quantity' ) , $location , 'Add Units' , 0 );
                    }
                endif;
                $msg = "Inventory stock was successfully created";

                /* $id = Input::get( 'inventory_id' );
                  $stock_id = Input::get( 'stock_id' );
                  $create_trans = Input::get( 'create_trans' );
                  $stock_split = isset( $stock_id ) ? InventoryStock::find( Input::get( 'stock_id' ) ) : null;
                  if ( $stock_split != null && isset( $stock_id ) ) {

                  $qty = $stock_split->quantity - Input::get( 'quantity' );
                  $stock_split->quantity = $qty;
                  $stock_split->save();
                  $inventoryStock->fill( Input::all() );
                  $inventoryStock->user_id = Auth::user()->id;
                  $inventoryStock->save();
                  $msg = "Inventory stock was successfully splited";
                  }
                  else {

                  $inventoryStock->fill( Input::all() );
                  $inventoryStock->user_id = Auth::user()->id;
                  $inventoryStock->save();
                  $is_assembly = Input::get( 'is_assembly' );
                  if ( $is_assembly == 1 ) {

                  $location_id = Input::get( 'location_id' );

                  return Redirect::to( 'inventory/kits/create/' . $inventoryStock->id );

                  // return view( 'packages::kits.create' , compact( 'kitname' , 'kit_id' , 'page_title' , 'url' , 'btn_title' , 'inventory_lists' ) );
                  }
                  } */
                return Redirect::to( ( route( 'stocks' , $item->id ) ) )
                                ->with( 'flash_alert_notice' , $msg )->with( 'alert_class' , 'alert-success alert-dismissable' );
            }

            /**
             * Displays the form for editing the specified metric.
             *
             * @param int|string $id
             *
             * @return \Illuminate\View\View
             */
            public function edit( Inventory $inventory , InventoryStock $inventoryStock ) {

                $page_action_title = 'Edit';
                $page_title = 'Stocks Management';
                $item = $inventoryStock;

                $serial_num = '';
                if ( $item == null ) {
                    return Redirect::to( 'inventory/stocks' );
                }
                else {
                    $serial_num = $inventory->is_serialno;
                    $inventory_id = $inventoryStock->inventory_id;
                }
                $category_lists = Location::lists( 'name' , 'id' )->all();
                $inventory_lists = Inventory::lists( 'name' , 'id' )->all();

                $btn_title = 'Update';
                //echo '<pre>';print_r($inventory);echo '</pre>';die;
                return view( 'packages::inventories.stocks.edit' , compact( 'inventory' , 'inventoryStock' , 'inventory_id' , 'serial_num' , 'item' , 'page_action_title' , 'page_title' , 'btn_title' , 'category_lists' , 'inventory_lists' ) );
            }

            /**
             * Displays the form for editing the specified metric.
             *
             * @param int|string $id
             *
             * @return \Illuminate\View\View
             */
            public function kit_edit( $inventory , $inventoryStock ) {
                $inventory = Inventory::find( $inventory );
                $inventoryStock = InventoryStock::find( $inventoryStock );
                if ( $inventory->is_assembly == 0 ) {
                    return Redirect::to( route( 'stocks' , $inventory->id ) );
                }
                $parts = $inventory->getAssemblyItems();
                $page_action_title = 'Edit Kit - ' . $inventory->name;
                $page_title = 'Stocks Management';
                $used_stocks = [ ];
                foreach ( $inventoryStock->kits()->get() as $stock ):
                    $used_stocks[ $stock->id ] = $stock->pivot->quantity;
                endforeach;
                foreach ( $parts as $part ):
                    $part->stocks = $part->stocks()->where( 'location_id' , $inventoryStock->location_id )->get();
                    $part->usedQuantity = 0;
                    $i = 0;
                    foreach ( $part->stocks as $stock ):
                        // If the stock quantity is zero and the stock is not in use for this kit, don't show it on edit page
                        if ( $stock->quantity == 0 && !isset( $used_stocks[ $stock->id ] ) ):
                            unset( $part->stocks[ $i ] );
                            $i--;
                        else:
                            if ( isset( $used_stocks[ $stock->id ] ) ):
                                $stock->usedQuantity = $used_stocks[ $stock->id ];
                                $stock->quantity += $stock->usedQuantity;
                            else:
                                $stock->usedQuantity = 0;
                            endif;
                            $part->usedQuantity += $stock->usedQuantity;
                        endif;
                        $i++;
                    endforeach;
                    $part->missedQuantity = $part->pivot->quantity - $part->usedQuantity;
                endforeach;

                \JavaScript::put( [
                    'parts' => $parts
                        ]
                );
                $btn_title = 'Update';
                return view( 'packages::inventories.stocks.kits.edit' , compact( 'inventory' , 'inventoryStock' , 'serial_num' , 'item' , 'page_action_title' , 'page_title' , 'btn_title' , 'category_lists' , 'inventory_lists' ) );
            }

            public function kit_update( $inventory , $inventoryStock ) {

                $inventory = Inventory::find( $inventory );
                $inventoryStock = InventoryStock::find( $inventoryStock );
                if ( $inventory->is_assembly == 0 ) {
                    return Redirect::to( route( 'stocks' , $inventory->id ) );
                }
                $stock_quantity = Input::get( 'stock_quantity' );
                $stock_id = Input::get( 'stock_id' );
                $i = 0;
                foreach ( $inventoryStock->kits as $stock ):
                    $inventoryStock->kits()->detach( $stock->id );
                    $stock->put( $stock->pivot->quantity );
                endforeach;
                foreach ( $stock_quantity as $stock ):
                    if ( $stock > 0 ):
                        $inventoryStock->kits()->attach( $stock_id[ $i ] , ['quantity' => $stock ] );
                        $current_stock = InventoryStock::find( $stock_id[ $i ] );
                        $current_stock->take( $stock );
                    endif;
                    $i++;
                endforeach;
            }

            /**
             * Updates the specified metric.
             *
             * @param SupplierRequest $request
             * @param int|string    $id
             *
             * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
             */
            public function update( Inventory $inventory , InventoryStock $inventoryStock ) {
                $inventoryStock->fill( Input::all() );
                $inventoryStock->location_id = $inventoryStock->location_id;
                $inventoryStock->quantity = $inventoryStock->quantity;
                $inventoryStock->user_id = Auth::user()->id;
                $inventoryStock->save();
                return Redirect::to( route( 'stocks' , $inventory->id ) )
                                ->with( 'flash_alert_notice' , 'Stock was successfully updated' )->with( 'alert_class' , 'alert-success alert-dismissable' );
            }

            public function destroy( InventoryStock $inventoryStock ) {

                $inventry_id = Input::get( 'inventory_id' );

                $is_delete = Helper::check_stock_in_kit( $inventoryStock->id );

                if ( $is_delete > 0 ) {
                    die( 'Access denied' );
                }

                InventoryStock::destroy( $inventoryStock->id );
                $msg = 'Stocks was successfully deleted!';
                $check = InventoryStock::where( 'inventory_id' , $inventoryStock->id )->get();
                if ( $check->count() > 0 ) {
                    return Redirect::to( 'inventory/stocks/' . $id )
                                    ->with( 'flash_alert_notice' , "Stock deleted successfully !" )->with( 'alert_class' , 'alert-success alert-dismissable' );
                }
                return Redirect::to( 'inventory/stocks?id=' . $inventry_id )
                                ->with( 'flash_alert_notice' , $msg )->with( 'alert_class' , 'alert-danger alert-dismissable' );
            }

        }
