<?php

namespace Inventory\Admin\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
//use Illuminate\Http\Request;
use Illuminate\View;
use Input;
use Validator;
use Inventory\Admin\Models\Inventory;
use Inventory\Admin\Models\Category;
use Inventory\Admin\Models\Kits;
use Inventory\Admin\Models\KitStockMap;
use Inventory\Admin\Models\InventoryStock;
use Inventory\Admin\Http\Requests\StocksRequest;
use Inventory\Admin\Models\InventoryTransactionState;
use Inventory\Admin\Models\InventoryTransaction;
use Inventory\Admin\Models\InventoryStockMovement;
use Inventory\Admin\Models\Assembly;
use Inventory\Admin\Models\Location;
use Inventory\Admin\Models\BulkTransaction;
use Inventory\Admin\Traits\InventoryStockMovementTrait;
use Auth;
use Paginate;
use Request;
use Session;
use Menu;
use App\Http\Controllers\Controller;
use App\Helpers\Helper as Helper;

//use View;

class InventoryKitsController extends Controller {

    public function __construct() {
        $this->middleware( 'auth' );
        parent::__construct();
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index( $kit_id = '' ) {

        $results = null;
        $page_title = Config::get( 'app.pageActionTitleKitIndex' );
        $state_lists = InventoryTransactionState::lists( 'state' , 'state' )->all();

        $kitStockObj = new InventoryStock;
        $results = $kitStockObj->with( 'inventory' )
                        ->where( 'inventory_id' , $kit_id )->paginate( '10' );
        $error_msg = '';
        if ( $results->count() == 0 ) {
            $error_msg = "Kit stock not available";
        }
        $kit_lists = Inventory::where( 'is_assembly' , 1 )->lists( 'name' , 'id' )->all();
        $kit_type_name = Inventory::find( $kit_id );


        return view( 'packages::kits.index' , compact( 'kit_type_name' , 'kit_lists' , 'state_lists' , 'results' , 'kit_id' , 'page_title' , 'error_msg' ) );
    }

    /**
     * Displays the form to create a Inventory.
     *
     * @return \Illuminate\View\View
     */
    public function create( $id = '' ) {

        if ( Request::ajax() ) {
            $all_state = Input::get( 'state' );
            $all_inventory_id = Input::get( 'inventory_id' );
            $transaction_name = Input::get( 'name' );
            $all_quantity = Input::get( 'quantity' );

            $bulk_id = time() . '-' . rand( 1 , 999 );

            foreach ( $all_state as $key => $state ) {

                if ( !empty( $all_state[ $key ] ) ) {
                    $stock_result = InventoryStock::where( 'inventory_id' , $all_inventory_id[ $key ] )->get();
                    $transaction_name = $transaction_name[ $key ];
                    $qty = $all_quantity[ $key ];
                    $state_name = $state; //'state2';

                    if ( $stock_result->count() > 0 ) {

                        foreach ( $stock_result as $key => $stock ) {
                            $stock = InventoryStock::find( $stock->id );
                            $transaction = $stock->newTransaction( $transaction_name );
                            $transaction->state = $state_name;
                            $transaction->quantity = $qty;
                            $transaction->save();
                            $transaction_id = InventoryTransaction::orderBy( 'id' , 'desc' )->first();
                            $bulk_transaction = new BulkTransaction;
                            $bulk_transaction->bulk_id = $bulk_id;
                            $bulk_transaction->transaction_id = $transaction_id->id;
                            $bulk_transaction->save();
                        }
                    }
                }
            }
            die( 'Transaction created' );
        }
        $error_msg = '';
        $stock_record = InventoryStock::find( $id );

        $kit_unit_name = Inventory::find( $stock_record->inventory_id );

        $page_title = 'Create Kit Stock' . ' - ' . $kit_unit_name [ 'name' ];
        $location_id = $stock_record[ 'location_id' ];
        $location_name = Location::find( $location_id );
        $assemply = Assembly::where( 'inventory_id' , $stock_record->inventory_id )->get();
        $qty = [ ];
        $i = 0;
        $j = 0;
        $stock_list = [ ];
        $total_kit_qty = 0;
        $arr = [ ];

        if ( count( $assemply ) == 0 ) {
            $error_msg = "Stock not available for this kit ";
        }
        $count = 0;
        foreach ( $assemply as $key => $value ) {
            $total_kit_qty = $total_kit_qty + $value->quantity;
            $inventory_name = Inventory::find( $value->part_id );
            $stock_result = InventoryStock::
                            with( 'inventoryName' , 'kitStockMapRelation' )
                            ->whereRaw( 'inventory_stocks.id NOT IN (SELECT stock_id FROM kit_stock_map)' )
                            ->where( 'location_id' , $location_id )->where( 'inventory_id' , '=' , $value->part_id )->get();


            $arr = [ ];
            if ( $stock_result->count() > 0 ) {
                $count++;
                foreach ( $stock_result as $key => $stock_name ) {
                    $arr[ $i ][ 'stock_name' ] = $stock_name[ 'inventoryName' ][ 'name' ];
                    $arr[ $i ][ 'stock_id' ] = $stock_name->id;
                    $arr[ $i ][ 'stock_quantity' ] = $stock_name->quantity;
                    $arr[ $i ][ 'stock_inventory_id' ] = $stock_name->inventory_id;
                    $arr[ $i ][ 'qty' ] = $value->quantity;
                    $arr[ $i ][ 'inventory_id' ] = $value->part_id;
                    $arr[ $i ][ 'location_id' ] = $location_id;
                    $arr[ $i ][ 'serial_no' ] = $stock_name->serial_no;
                    $arr[ $i ][ 'max_quantity' ] = $value->quantity;
                    $i++;
                }
                $stock_list[ $inventory_name[ 'name' ] ] = $arr;
                $qty[] = $value->quantity;
            }
        }

        $kit_map_record = KitStockMap::where( 'kit_stock_id' , $id )->get();
        foreach ( $kit_map_record as $key => $value ) {
            $kitmap_record[ $j ][ 'id' ] = $value->id;
            $kitmap_record[ $j ][ 'kit_stock_id' ] = $value->kit_stock_id;
            $kitmap_record[ $j ][ 'stock_id' ] = $value->stock_id;
            $kitmap_record[ $j ][ 'quantity' ] = $value->quantity;
            $is_checked[] = '';
            $j++;
        }
        $offset = 0;
        $url = 'inventory/kitstocks/store';
        $kit_id = $stock_record->inventory_id;
        return view( 'packages::kits.create' , compact( 'qty' , 'offset' , 'kitmap_record' , 'is_checked' , 'total_kit_qty' , 'location_name' , 'arr' , 'stock_list' , 'kit_id' , 'page_action_title' , 'page_title' , 'url' , 'id' , 'count' ) );
    }

    /**
     *
     *
     * @param Kit $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store() {

        $kit_id = Input::get( 'kit_id' );
        $validator = count( Input::get( 'inventory_id' ) );
        if ( is_numeric( $kit_id ) == false ) {
            return Redirect::to( 'inventory/kits' );
        }
        if ( $validator == 0 ) {
            return Redirect::to( 'inventory/kits/create/' . Input::get( 'stock_kit' ) )
                            ->with( 'flash_alert_notice' , 'Please select Stock Kit' )->with( 'alert_class' , 'alert-danger alert-dismissable' );
        }
        else {

            $inventory_id = Input::get( 'inventory_id' );
            $quantity = Input::get( 'quantity' );
            foreach ( $inventory_id as $key => $value ) {
                Kits::where( 'kit_id' , $kit_id )->where( 'inventory_id' , $value )->delete();
                $obj = new Kits;
                $obj->kit_id = Input::get( 'kit_id' );
                $obj->inventory_id = $value;
                $obj->quantity = $quantity[ $key ];
                $obj->save();
            }

            return Redirect::to( 'inventory/inventories/' )
                            ->with( 'flash_alert_notice' , 'inventory Kit has been created successfully !' )->with( 'alert_class' , 'alert-success alert-dismissable' );
        }
    }

    /**
     * Displays the form for editing the specified metric.
     *
     * @param int|string $id
     *
     * @return \Illuminate\View\View
     */
    public function edit( $id = '' ) {
        $error_msg = '';
        $stock_record = InventoryStock::find( $id );
        $kit_unit_name = Inventory::find( $stock_record->inventory_id );
        $location_id = $stock_record[ 'location_id' ];
        $location_name = Location::find( $location_id );
        $assemply = Assembly::where( 'inventory_id' , $stock_record->inventory_id )->get();
        $qty = [ ];
        $stock_list = [ ];
        $total_kit_qty = 0;
        $arr = [ ];
        foreach ( $assemply as $key => $value ) {

            $total_kit_qty = $total_kit_qty + $value->quantity;
            $inventory_name = Inventory::find( $value->part_id );
            //  $stock_result = InventoryStock::with( 'inventoryName' )->where( 'inventory_id' , $value->part_id )->where( 'location_id' , $location_id )->where( 'inventory_id' , '!=' , $stock_record->inventory_id )->get();
            $stock_result = InventoryStock::
                            with( 'inventoryName' , 'kitStockMapRelation' )
                            ->whereRaw( 'inventory_stocks.id NOT IN (SELECT stock_id FROM kit_stock_map)' )
                            ->where( 'location_id' , $location_id )->where( 'inventory_id' , '=' , $value->part_id )->get();


            $arr = [ ];
            $i = 0;
            if ( $stock_result->count() > 0 ) {

                foreach ( $stock_result as $key => $stock_name ) {
                    $arr[ $i ][ 'stock_name' ] = $stock_name[ 'inventoryName' ][ 'name' ];
                    $arr[ $i ][ 'stock_id' ] = $stock_name->id;
                    $arr[ $i ][ 'stock_quantity' ] = $stock_name->quantity;
                    $arr[ $i ][ 'stock_inventory_id' ] = $stock_name->inventory_id;
                    $arr[ $i ][ 'qty' ] = $value->quantity;
                    $arr[ $i ][ 'inventory_id' ] = $value->part_id;
                    $arr[ $i ][ 'location_id' ] = $location_id;
                    $arr[ $i ][ 'serial_no' ] = $stock_name->serial_no;
                    $arr[ $i ][ 'max_quantity' ] = $value->quantity;
                    $i++;
                }

                $stock_list[ $inventory_name[ 'name' ] ] = $arr;

                $qty[] = $value->quantity;
            }
        }
        // dd( $qty );
        $kit_map_record = KitStockMap::where( 'kit_stock_id' , $id )->get();
        $j = 0;
        $offset = 0;
        if ( $kit_map_record->count() == 0 ) {
            return redirect::to( 'inventory/kits/create/' . $id );
        }
        foreach ( $kit_map_record as $key => $value ) {
            $kitmap_record[ $j ][ 'id' ] = $value->id;
            $kitmap_record[ $j ][ 'kit_stock_id' ] = $value->kit_stock_id;
            $kitmap_record[ $j ][ 'stock_id' ] = $value->stock_id;
            $kitmap_record[ $j ][ 'quantity' ] = $value->quantity;
            $is_checked[] = $value->stock_id;
            $j++;
        }

        $kitname = InventoryStock::with( 'inventoryName' )->where( 'location_id' , $location_id )->get();
        $url = 'inventory/kitstocks/update/' . $id;
        $page_title = 'Edit Kit Stock' . ' - ' . $kit_unit_name [ 'name' ];
        $kit_id = $stock_record->inventory_id;

        return view( 'packages::kits.edit' , compact( 'kitmap_record' , 'offset' , 'is_checked' , 'qty' , 'kit_map_record' , 'total_kit_qty' , 'location_name' , 'arr' , 'stock_list' , 'kit_id' , 'page_action_title' , 'page_title' , 'url' , 'id' , 'error_msg' ) );
    }

    /**
     * Updates the specified metric.
     *
     * @param SupplierRequest $request
     * @param int|string    $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update( $id ) {

        $validator = Kits::isValidate();

        if ( $validator->fails() ) {

            return Redirect::to( 'inventory/kits/edit/' . $id )
                            ->withErrors( $validator );
        }
        else {
            Kits::where( 'kit_id' , $id )->delete();
            $inventory_id = Input::get( 'inventory_id' );
            $quantity = Input::get( 'quantity' );

            foreach ( $inventory_id as $key => $value ) {
                $obj = new Kits;
                $obj->kit_id = Input::get( 'kit_id' );
                $obj->inventory_id = $value;
                $obj->quantity = $quantity[ $key ];
                $obj->save();
            }

            return Redirect::to( 'inventory/inventories' )
                            ->with( 'flash_alert_notice' , 'Inventory Kits has been updated successfully!' )->with( 'alert_class' , 'alert-success alert-dismissable' );
        }
    }

    public function editkitstocks( $id ) {

        $helperObj = new Helper;
        $inventoryMenu = $helperObj->inventoryMenu();
        $userMenu = $helperObj->userMenu();
        $locationMenu = $helperObj->locationMenu();
        $inventoryMenu = $helperObj->inventoryMenu();
        $stocksMenu = $helperObj->stocksMenu();
        $metricsMenu = $helperObj->metricsMenu();
        $supplierMenu = $helperObj->supplierMenu();


        $page_action_title = 'Edit kitstocks';
        $page_title = 'Inventory Management';
        $item = Inventory::find( $id );

        if ( $item == null ) {
            return Redirect::to( 'inventory/inventories' );
        }
        $kitname = Inventory::where( 'id' , $id )->lists( 'name' )->all();
        $url = 'inventory/kits/store';
        $inventory_lists = Inventory::all();
        $kit_id = $id;

        $ret = new Kits;
        $results = $ret->with( 'inventoryName' )->where( 'kit_id' , $id )->lists( 'inventory_id' )->all();
        $quantity = $ret->with( 'inventoryName' )->where( 'kit_id' , $id )->lists( 'quantity' )->all();
        //print_r($quantity);die;
        $url = 'inventory/kits/update/' . $id;

        $btn_title = 'Update';
        //echo '<pre>';print_r($inventory);echo '</pre>';die;
        return view( 'packages::kits.edit' , compact( 'quantity' , 'results' , 'kitname' , 'kit_id' , 'item' , 'url' , 'page_action_title' , 'page_title' , 'btn_title' , 'category_lists' , 'metric_lists' , 'inventory_lists' , 'metricsMenu' , 'stocksMenu' , 'userMenu' , 'locationMenu' , 'supplierMenu' , 'inventoryMenu' ) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy( $id ) {

        Kits::destroy( $id );
        return Redirect::to( '/inventory/inventories' )
                        ->with( 'flash_alert_title' , 'OK' )->with( 'flash_alert_notice' , 'Inventory kits was successfully deleted!' )->with( 'alert_class' , 'alert-success alert-dismissable' );
    }

}
