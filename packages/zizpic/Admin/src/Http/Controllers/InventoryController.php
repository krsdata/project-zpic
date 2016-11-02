<?php

namespace Inventory\Admin\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Request;
use Illuminate\View;
use Input;
use Validator;
use Inventory\Admin\Models\Inventory;
use Inventory\Admin\Models\Category;
use Inventory\Admin\Http\Requests\InventoryRequest;
use Inventory\Admin\Models\Metric;
use Inventory\Admin\Models\Kits;
use Inventory\Admin\Models\InventoryTransactionState;
use Inventory\Admin\Models\BulkTransaction;
use Inventory\Admin\Models\InventoryStock;
use Inventory\Admin\Models\InventoryTransaction;
use Auth;
use Paginate;
use Menu;
use App\Helpers\Helper as Helper;
use Grids;
use HTML;
use Form;
use Nayjest\Grids\SelectFilterConfig;
use Nayjest\Grids\Components\Base\RenderableRegistry;
use Nayjest\Grids\Components\ColumnHeadersRow;
use Nayjest\Grids\Components\ColumnsHider;
use Nayjest\Grids\Components\CsvExport;
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
use Nayjest\Grids\Components\ExcelExport;

//use View;

class InventoryController extends Controller {

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


        $page_title = Config::get( 'app.pageActionTitleInventoryIndex' );
        $page_action_title = 'Inventory';
        $grid = $this->generateGrid( $page_action_title );
        $grid->setDataProvider(
                new EloquentDataProvider( Inventory::query()->orderBy( 'id' , 'DESC' ) )
        );
        $grid->setColumns( [
                    (new FieldConfig )
                    ->setName( 'id' )
                    ->setLabel( 'ID' )
                    ->setSortable( true )
                    ->setSorting( Grid::SORT_ASC ) ,
                    (new FieldConfig )
                    ->setName( 'name' )
                    ->setLabel( 'Name' )
                    ->setCallback( function ($val) {
                        return $val;
                    } )
                    ->setSortable( true )
                    ->addFilter(
                            (new FilterConfig )
                            ->setOperator( FilterConfig::OPERATOR_LIKE )
                    ) ,
                    (new FieldConfig )
                    ->setName( 'updated_at' )
                    ->setLabel( 'Date' )
                    ->setSortable( false )
            ,
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
                    ->setName( 'is_assembly' )
                    ->setLabel( 'Type' )
                    ->setCallback( function ($val , $row) {
                        if ( $val == 1 ):
                            return ucfirst( Config::get( 'app.assembly' ) );
                        endif;
                        return ucfirst( Config::get( 'app.part' ) );
                    } )
                    ->setSortable( true )
                    ->addFilter(
                            (new SelectFilterConfig )
                            ->setName( 'is_assembly' )
                            ->setOptions( [0 => ucfirst( Config::get( 'app.part' ) ) , 1 => ucfirst( Config::get( 'app.assembly' ) ) ] )
                    ) ,
                    (new FieldConfig )
                    ->setName( 'actions' )
                    ->setLabel( 'Actions' )
                    ->setCallback( function ($val , $row ) {
                        $attr = $row->getSrc();
                        $btn = Form::button( '' , array( 'class' => 'no-style fa fa-pencil-square-o' ) );
                        if ( $attr->is_kit == 1 ):
                            $edit_url = url( 'inventory/kits/edit/' . $attr->id );
                        else:
                            $edit_url = route( 'inventories.edit' , $attr->id );
                        endif;
                        $html = '<a class="pull-left" href="' . $edit_url . '" >' . $btn . '</a>';
                        $html .= Form::open( array( 'class' => 'form-inline pull-left deletion-form' , 'method' => 'DELETE' , 'route' => array( 'inventories.destroy' , $attr->id ) ) );
                        $html .= Form::button( '' , array( 'class' => 'no-style fa fa-trash-o delete-Btn' , 'type' => 'submit' ) );
                        $html .= Form::close();
                        if ( $attr->is_assembly == 1 ):
                            $html .= '<a class="btn-primary btn btn-xs" href="' . route( 'stocks.create' , $attr->id ) . '" ><span class="fa fa-plus"></span> Add Kits</a>';
                        //$html .= '<a class="btn-primary btn btn-xs" href="' . url( 'inventory/kits/' . $attr->id ) . '" ><span class="fa fa-eye"></span> View Kits</a>';
                        else:
                            $html .= '<a class="btn-primary btn btn-xs" href="' . route( 'stocks.create' , $attr->id ) . '" ><span class="fa fa-plus"></span> Add Unit</a>';
                        endif;
                        $html .= '<a class="btn-primary btn btn-xs" href="' . route( 'stocks' , $attr->id ) . '" ><span class="fa fa-eye"></span> View Stock</a>';
                        return $html;
                    } )
        ] );
        $grid = new Grid( $grid );
        $grid = $grid->render();
        $kit_lists = Inventory::where( 'is_kit' , 1 )->lists( 'name' , 'id' )->all();
        $state_lists = InventoryTransactionState::lists( 'state' , 'state' )->all();
        $routes = ['create' => 'inventories.create' ];
        return $this->view( 'packages::inventories.index' , compact( 'kit_lists' , 'state_lists' , 'grid' , 'page_action_title' , 'page_title' , 'routes' ) );
    }

    /** pageActionTitleEdit
     * Displays the form to create a Inventory.
     *
     * @return \Illuminate\View\View
     */
    public function create( Inventory $inventory ) {

        if ( Request::ajax() ) {

            $kit_lists = Input::get( 'kit_lists' );

            $last_bulk_id = BulkTransaction::orderBy( 'id' , 'desc' )->get();
            $c = $last_bulk_id->count();
            if ( $c == 0 ) {
                $bulk_id = 1;
            }
            else {
                $bulk_id = $last_bulk_id[ 0 ][ 'bulk_id' ] + 1;
            }

            foreach ( $kit_lists as $key => $kit ) {

                $kit_id = $kit;
                $kitObj = new Kits;
                $kits_inventory = Kits::where( 'kit_id' , $kit_id )->get();

                foreach ( $kits_inventory as $key => $value ) {

                    $stock_result = InventoryStock::where( 'inventory_id' , $value->inventory_id )->get();
                    $transaction_name = Input::get( 'name' );
                    $qty = Input::get( 'quantity' );
                    $state_name = Input::get( 'state' );

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

                $page_title = Config::get( 'app.pageActionTitleInventoryCreate' );
                $btn_title = 'Save';
                $category_lists = Category::lists( 'name' , 'id' );
                // $metric_lists = Metric::where( 'user_id' , Auth::user()->id )->lists( 'name' , 'id' );
                $metric_lists = Metric::lists( 'name' , 'id' );
                $inventory_lists = Inventory::where( 'is_assembly' , 0 )->lists( 'name' , 'id' )->all();
                //$inventory_lists = Inventory::get()->lists( 'name' , 'id' )->all();

                return view( 'packages::inventories.create' , compact( 'inventory' , 'page_action_title' , 'page_title' , 'btn_title' , 'category_lists' , 'metric_lists' , 'inventory_lists' ) );
            }
            die( 'Transaction created' );
        }


        $page_title = Config::get( 'app.pageActionTitleInventoryCreate' );
        $btn_title = 'Save';
        $category_lists = Category::lists( 'name' , 'id' );
        $metric_lists = Metric::lists( 'name' , 'id' );
        $inventory_lists = Inventory::where( 'is_kit' , 0 )->lists( 'name' , 'id' )->all();
        \JavaScript::put( [
            'inventory' => new Inventory ,
            'parts'     => [ ]
        ] );
        return view( 'packages::inventories.create' , compact( 'inventory' , 'page_action_title' , 'page_title' , 'btn_title' , 'category_lists' , 'metric_lists' , 'inventory_lists' ) );
    }

    /**
     * Creates a metric.
     *
     * @param SupplierRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store( InventoryRequest $request , Inventory $inventory ) {


        $inventory->fill( Input::all() );
        $inventory->user_id = Auth::user()->id;
        if ( Input::get( 'parent_id' ) == '' ):
            $parent_id = null;
        else:
            $parent_id = Input::get( 'parent_id' );
        endif;
        $inventory->parent_id = $parent_id;
        $inventory->save();
        if ( Input::get( 'is_assembly' ) == '1' ):
            $assembly_part_qt = Input::get( 'assembly_part_qt' );
            $assembly_part_id = Input::get( 'assembly_part_id' );
            $i = 0;
            $parts = [ ];
            $part_count = count( $assembly_part_qt );
            if ( $part_count > 0 ) {
                foreach ( $assembly_part_qt as $assembly_part ):
                    if ( $assembly_part_qt[ $i ] > 0 ):
                        if ( !isset( $parts[ $assembly_part_id[ $i ] ] ) ):
                            $parts[ $assembly_part_id[ $i ] ] = $assembly_part_qt[ $i ];
                        else:
                            $parts[ $assembly_part_id[ $i ] ] += $assembly_part_qt[ $i ];
                        endif;
                    endif;
                    $i++;
                endforeach;
                foreach ( $parts as $part_id => $part_qt ):
                    $inventory->addAssemblyItem( Inventory::find( $part_id ) , $part_qt );
                endforeach;
            }

        endif;
        return Redirect::to( route( 'inventories' ) )
                        ->with( 'flash_alert_notice' , 'Inventory item was successfully created' )->with( 'alert_class' , 'alert-success alert-dismissable' );
    }

    /**
     * Displays the form for editing the specified metric.
     *
     * @param int|string $id
     *
     * @return \Illuminate\View\View
     */
    public function edit( Inventory $inventory ) {


        $page_title = Config::get( 'app.pageActionTitleEdit' ) . ' - ' . $inventory->name;
        $item = $inventory;
        $parts = [ ];
        if ( $inventory->is_assembly == 1 ):
            $items = $inventory->getAssemblyItems(); // Returns an Eloquent Collection
            foreach ( $items as $item ) {
                $parts[] = ['part_name' => $item->name , 'part_quantity' => $item->pivot->quantity ];
            }
        endif;
        $category_lists = Category::lists( 'name' , 'id' );
        //$metric_lists = Metric::where( 'user_id' , Auth::user()->id )->lists( 'name' , 'id' );
        $metric_lists = Metric::lists( 'name' , 'id' );
        $inventory_lists = Inventory::lists( 'name' , 'id' );
        \JavaScript::put( [
            'inventory' => $inventory ,
            'parts'     => $parts
        ] );
        return view( 'packages::inventories.edit' , compact( 'item' , 'inventory' , 'page_action_title' , 'page_title' , 'category_lists' , 'metric_lists' , 'inventory_lists' ) );
    }

    /**
     * Updates the specified metric.
     *
     * @param SupplierRequest $request
     * @param int|string    $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update( InventoryRequest $request , Inventory $inventory ) {
        $inventory->fill( Input::all() );
        $inventory->user_id = Auth::user()->id;
        $inventory->is_serialno = (Input::has( 'is_serialno' ) ? 1 : 0);
        $inventory->save();
        return Redirect::to( route( 'inventories' ) )
                        ->with( 'flash_alert_notice' , 'Inventory was successfully updated!' )->with( 'alert_class' , 'alert-success alert-dismissable' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy( Inventory $inventory ) {

        $check = Inventory::where( 'parent_id' , $inventory->id )->get();
        if ( $check->count() > 0 ) {
            return Redirect::to( '/inventory/inventories' )
                            ->with( 'flash_alert_title' , 'OK' )->with( 'flash_alert_notice' , "You can't  delete !" )->with( 'alert_class' , 'alert-danger alert-dismissable' );
        }
        Inventory::destroy( $inventory->id );
        return Redirect::to( '/inventory/inventories' )
                        ->with( 'flash_alert_title' , 'OK' )->with( 'flash_alert_notice' , 'Inventory was successfully deleted!' )->with( 'alert_class' , 'alert-success alert-dismissable' );
    }

}
