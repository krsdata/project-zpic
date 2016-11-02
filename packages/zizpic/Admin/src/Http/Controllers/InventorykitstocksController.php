<?php

namespace Inventory\Admin\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\View;
use Input;
use Validator;
use Inventory\Admin\Models\Inventory;
use Inventory\Admin\Models\Category;
use Inventory\Admin\Models\Kits;
use Inventory\Admin\Models\KitStockMap;
use Inventory\Admin\Models\InventoryStock;
use Auth;
use Paginate;
use Menu;
use App\Helpers\Helper as Helper;
use App\Http\Controllers\Controller;

class InventoryKitstocksController extends Controller {

    public function __construct() {
        $this->middleware( 'auth' );
        parent::__construct();
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index() {

        $id = "";
        $results = null;
        $page_title = Config::get( 'app.pageActionTitleKitStockIndex' );
        $objKitStockMap = new KitStockMap;
        $inventoryObj = Inventory::all();
        $kitStockMapResults = $objKitStockMap->with( 'inventoryStockRelation' )->orderBy( 'id' , 'desc' )->paginate( Config::get( 'app.NumberOfPerPage' ) );
        if ( $kitStockMapResults->count() == 0 ) {
            return Redirect::to( 'inventory/inventories/' )
                            ->with( 'flash_alert_notice' , 'There is no KIT Stocks ' )->with( 'alert_class' , 'alert-danger alert-dismissable' );
        }
        $stocksKit = array();
        foreach ( $kitStockMapResults as $key => $value ) {

            $inventoryObj = Inventory::find( $value->inventoryStockRelation[ 'inventory_id' ] );

            if ( isset( $inventoryObj[ 'name' ] ) ) {
                $stocksKit[ 'stockname' ][] = $inventoryObj[ 'name' ];
                $objInventory = Inventory::find( $value->kit_stock_id );
                $stocksKit[ 'kitname' ][] = $objInventory[ 'name' ];
                $stocksKit[ 'id' ][] = $value->id;
                $stocksKit[ 'kit_stock_id' ][] = $value->kit_stock_id;
                $stocksKit[ 'quantity' ][] = $value->quantity;
                $stocksKit[ 'serial_no' ][] = $value->serial_no;
            }
        }
        $kitStockMapResults->setPath( 'kitstocks' );
        return view( 'packages::kitstocks.index' , compact( 'kitStockMapResults' , 'stocksKit' , 'page_action_title' , 'page_title' ) );
    }

    /**
     * Displays the form to create a Inventory.
     *
     * @return \Illuminate\View\View
     */
    public function createkitstocks( $id = '' ) {

        $page_title = Config::get( 'app.pageActionTitleKitStockCreate' );

        $url = 'inventory/kitstocks/store';
        $inventory_lists = Inventory::all();

        $kitObject = new Kits;
        $kitResults = $kitObject->with( 'inventoryName' , 'inventoryStockRelation' )->where( 'kit_id' , $id )->get();

        $kitname_lists = Inventory::where( 'is_kit' , 1 )->lists( 'name' , 'id' )->all();

        return view( 'packages::kitstocks.create' , compact( 'id' , 'kitResults' , 'kitname_lists' , 'kit_id' , 'page_action_title' , 'page_title' , 'url' , 'btn_title' , 'inventory_lists' ) );
    }

    /**
     *
     *
     * @param SupplierRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function storekitstocks() {

        $kit_id = Input::get( 'kit_id' );
        $validator = kitStockMap::isValidate();
        if ( is_numeric( $kit_id ) == false ) {
            return Redirect::to( 'inventory/kitstocks' );
        }
        if ( $validator->fails() ) {
            return Redirect::to( 'inventory/kitstocks/create-kitstocks/' . $kit_id )
                            ->withErrors( $validator )
                            ->withInput();
        }
        else {

            $quantity = Input::get( 'quantity' );
            $serial_no = Input::get( 'serial_no' );
            $inventory_id = Input::get( 'inventory_id' );
            foreach ( $inventory_id as $key => $value ) {
                $obj = new KitStockMap;
                $obj->kit_stock_id = Input::get( 'kit_stock_id' );
                $obj->stock_id = $value;
                $obj->quantity = $quantity[ $key ];
                $obj->serial_no = $serial_no[ $key ];
                $obj->save();
            }

            return Redirect::to( 'inventory/kitstocks/' )
                            ->with( 'flash_alert_notice' , 'inventory Kit has been created successfully !' )->with( 'alert_class' , 'alert-success alert-dismissable' );
        }
    }

    /**
     *
     *
     * @param SupplierRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store() {

        $kit_id = Input::get( 'kit_id' );
        $kit_stock_id = Input::get( 'kit_stock_id' );
        $check_qty = Input::get( 'max_qty' );
        $isQtyAvailable = 0;
        foreach ( $check_qty as $key => $max_qty ) {

            $inventoryQty = Input::get( 'inventory_id_' . $key );
            $qty = 0;

            if ( is_array( $inventoryQty ) ) {
                foreach ( $inventoryQty as $invQty ) {
                    $tmp = explode( ":" , $invQty );
                    $qty+=$tmp[ 1 ];
                    $isQtyAvailable++;
                }

                if ( $max_qty < $qty ) {
                    return Redirect::to( 'inventory/kits/create/' . $kit_stock_id )->with( 'flash_alert_notice' , 'Quantity can not be greater than max quantity' )->with( 'alert_class' , 'alert-danger alert-dismissable' );
                }
            }
        }

        if ( $isQtyAvailable == 0 ) {
            return Redirect::to( 'inventory/kits/create/' . $kit_stock_id )->with( 'flash_alert_notice' , 'Please select the quantity.' )->with( 'alert_class' , 'alert-danger alert-dismissable' );
        }

        foreach ( $check_qty as $key => $max_qty ) {

            $inventory_id = Input::get( 'inventory_id_' . $key );
            if ( count( $inventory_id ) > 0 ) {


                foreach ( $inventory_id as $key => $id ) {
                    $stk_id = explode( ':' , $id );

                    $quantity = Input::get( 'quantity_' . $stk_id[ 0 ] );
                    $serial_no = Input::get( 'serial_no_' . $stk_id[ 0 ] );
                    $obj = new KitStockMap;
                    $obj->kit_stock_id = Input::get( 'kit_stock_id' );
                    $obj->stock_id = $stk_id[ 0 ];
                    $obj->quantity = $quantity;
                    $obj->created_by = Auth::user()->id;
                    $obj->serial_no = $serial_no;
                    $obj->save();
                }
            }
        }
        return Redirect::to( 'inventory/kits/' . $kit_id )
                        ->with( 'flash_alert_notice' , 'Inventory Kit has been created successfully !' )->with( 'alert_class' , 'alert-success alert-dismissable' );
    }

    /**
     * Displays the form for editing the specified metric.
     *
     * @param int|string $id
     *
     * @return \Illuminate\View\View
     */
    public function edit( $id = 0 ) {

        $item = Inventory::find( $id ); //  Stock name
        $page_title = Config::get( 'app.pageActionTitleEdit' );

        $inventory_lists = Inventory::all(); // All Inventory list
        $kitObject = new Kits;
        $kitResults = $kitObject->with( 'inventoryName' , 'inventoryStockRelation' )->where( 'kit_id' , $id )->get();
        $kitname_lists = Inventory::where( 'is_kit' , 1 )->lists( 'name' , 'id' )->all();
        $kitStocksObj = new KitStockMap;
        //$kitStocksResult = $kitStocksObj->with( 'inventoryStockRelation' , 'inventoryRelation' )->where( 'kit_stock_id' , $id )->get();
        $kitStocksResult = KitStockMap::where( 'kit_stock_id' , $id )->get();

        $url = "inventory/kitstocks/update/" . $id;
        $btn_title = 'Update';

        return view( 'packages::kitstocks.edit' , compact( 'id' , 'url' , 'kitStocksResult' , 'kitResults' , 'kitname_lists' , 'kit_id' , 'page_action_title' , 'page_title' , 'url' , 'btn_title' , 'inventory_lists' ) );
    }

    /**
     * Updates the specified metric.
     *
     * @param SupplierRequest $request
     * @param int|string    $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update( $id = 0 ) {

        $kit_id = Input::get( 'kit_id' );
        $kit_stock_id = Input::get( 'kit_stock_id' );
        $check_qty = Input::get( 'max_qty' );
        $isQtyAvailable = 0;
        foreach ( $check_qty as $key => $max_qty ) {
            $inventoryQty = Input::get( 'inventory_id_' . $key );
            $qty = 0;

            if ( is_array( $inventoryQty ) ) {
                foreach ( $inventoryQty as $invQty ) {
                    $tmp = explode( ":" , $invQty );
                    $qty+=$tmp[ 1 ];
                    $isQtyAvailable++;
                }

                if ( $max_qty < $qty ) {
                    return Redirect::to( 'inventory/kits/edit/' . $kit_stock_id )->with( 'flash_alert_notice' , 'Quantity can not be greater than max quantity' )->with( 'alert_class' , 'alert-danger alert-dismissable' );
                }
            }
        }

        if ( $isQtyAvailable == 0 ) {
            return Redirect::to( 'inventory/kits/edit/' . $kit_stock_id )->with( 'flash_alert_notice' , 'Please select quantity' )->with( 'alert_class' , 'alert-danger alert-dismissable' );
        }
        KitStockMap::where( 'kit_stock_id' , $kit_stock_id )->delete();

        foreach ( $check_qty as $key => $max_qty ) {
            $inventory_id = Input::get( 'inventory_id_' . $key );
            if ( count( $inventory_id ) > 0 ) {
                foreach ( $inventory_id as $key => $id ) {
                    $stk_id = explode( ':' , $id );
                    $quantity = Input::get( 'quantity_' . $stk_id[ 0 ] );
                    $serial_no = Input::get( 'serial_no_' . $stk_id[ 0 ] );
                    $obj = new KitStockMap;
                    $obj->kit_stock_id = Input::get( 'kit_stock_id' );
                    $obj->stock_id = $stk_id[ 0 ];
                    $obj->quantity = $quantity;
                    $obj->created_by = Auth::user()->id;
                    $obj->serial_no = $serial_no;
                    $obj->save();
                }
            }
        }
        return Redirect::to( 'inventory/kits/' . $kit_id )
                        ->with( 'flash_alert_notice' , 'Inventory Kit has been created successfully !' )->with( 'alert_class' , 'alert-success alert-dismissable' );
    }

    public function editkitstocks( $id ) {

        $page_action_title = 'Edit kitstocks';
        $page_title = 'Inventory Management';
        $item = Inventory::find( $id );

        if ( $item == null ) {
            return Redirect::to( 'inventory/inventories' );
        }
        $kitname = Inventory::where( 'id' , $id )->lists( 'name' )->all();
        $url = 'inventory/kitstocks/store';
        $inventory_lists = Inventory::all();
        $kit_id = $id;

        $ret = new Kits;
        $results = $ret->with( 'inventoryName' )->where( 'kit_id' , $id )->lists( 'inventory_id' )->all();
        $quantity = $ret->with( 'inventoryName' )->where( 'kit_id' , $id )->lists( 'quantity' )->all();
        //print_r($quantity);die;
        $url = 'inventory/kitstocks/update/' . $id;

        $btn_title = 'Update';
        //echo '<pre>';print_r($inventory);echo '</pre>';die;
        return view( 'packages::kitstocks.edit' , compact( 'quantity' , 'results' , 'kitname' , 'kit_id' , 'item' , 'url' , 'page_action_title' , 'page_title' , 'btn_title' , 'category_lists' , 'metric_lists' , 'inventory_lists' ) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy( $id ) {
        KitStockMap::destroy( $id );
        return Redirect::to( '/inventory/kitstocks' )
                        ->with( 'flash_alert_title' , 'OK' )->with( 'flash_alert_notice' , 'Inventory kits stock was successfully deleted!' )->with( 'alert_class' , 'alert-success alert-dismissable' );
    }

}
