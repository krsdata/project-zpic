<?php

namespace Inventory\Admin\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\View;
use Input;
use Validator;
use Inventory\Admin\Models\Location;
use Inventory\Admin\Models\InventoryStock;
use Inventory\Admin\Http\Requests\LocationRequest;
use Auth;
use Paginate;
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
use yajra\Datatables\Html\Builder;

/**
 * Class SupplierController
 */
class LocationController extends Controller {

    public function __construct() {
        $this->middleware( 'auth' );
        parent::__construct();
    }

    /**
     * @var SupplierRepository
     */
    protected $locations;

    /**
     * Displays all metrics.
     *
     * @return \Illuminate\View\View
     */
    public function getStock( Request $request , $location_id ) {
        $location = Location::find( $location_id );
        $stocks = \DB::table( 'inventory_stocks' )->join( 'inventories' , 'inventory_stocks.inventory_id' , '=' , 'inventories.id' )->select( ['inventory_stocks.id' , 'inventory_stocks.location_id' , 'inventory_stocks.quantity' , 'inventory_stocks.serial_no' , 'inventory_stocks.inventory_id' , 'inventories.name' , 'inventory_stocks.updated_at' ] )->where( 'location_id' , $location_id );

        return \Datatables::of( $stocks )->editColumn( 'serial_no' , function ($model) {
                    if ( $model->serial_no == '' )
                        return 'N\A';
                    return $model->serial_no;
                } )->editColumn( 'action' , function ($model) {
                    return '<a href="#" data-stock-serial_no="' . $model->serial_no . '" data-stock-id="' . $model->id . '" data-stock-quantity="' . $model->quantity . '" data-stock-location_id="' . $model->location_id . '" class="addStockRecord"><span class="fa fa-plus"></span></a>';
                } )->filter( function ($query) use ($request) {
                    if ( $request->has( 'inventory_id' ) ) {
                        $query->where( 'inventory_stocks.inventory_id' , $request->get( 'inventory_id' ) );
                    }
                } )->make( true );
    }

    public function index() {
        $page_action_title = 'Locations';
        $page_title = 'Locations Management';
        $grid = $this->generateGrid( $page_action_title );
        $grid->setDataProvider(
                new EloquentDataProvider( Location::query() )
        );
        $grid->setColumns( [
                    (new FieldConfig )
                    ->setName( 'id' )
                    ->setLabel( 'ID' )
                    ->setSortable( true )
                    ->setSorting( Grid::SORT_ASC )
            ,
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
                    )
            ,
                    (new FieldConfig )
                    ->setName( 'actions' )
                    ->setLabel( 'Actions' )
                    ->setCallback( function ($val , $row ) {
                        $attr = $row->getSrc();
                        $btn = Form::button( '' , array( 'class' => 'no-style fa fa-pencil-square-o' ) );

                        $html = '<a class="pull-left" href="' . route( 'locations.edit' , $attr->id ) . '" >' . $btn . '</a>';
                        $html .= Form::open( array( 'class' => 'form-inline pull-left deletion-form' , 'method' => 'DELETE' , 'route' => array( 'locations.destroy' , $attr->id ) ) );
                        $html .= Form::button( '' , array( 'class' => 'no-style fa fa-trash-o delete-Btn' , 'type' => 'submit' ) );
                        $html .= Form::close();
                        return $html;
                    } )
        ] );
        $grid = new Grid( $grid );
        $grid = $grid->render();
        $routes = ['create' => 'locations.create' ];
        return $this->view( 'packages::locations.index' , compact( 'routes' , 'grid' , 'page_action_title' , 'page_title' ) );
    }

    /**
     * Displays the form to create a metric.
     *
     * @return \Illuminate\View\View
     */
    public function create( Location $location ) {

        $category_lists = Location::lists( 'name' , 'id' )->all();
        $page_action_title = 'Create Location';
        $page_title = 'Create Location';
        $helper = new Helper;
        $data = $helper->getCustomFields( 'Location' );

        //dd( $custom_field );

        return view( 'packages::location.create' , compact( 'data' , 'location' , 'category_lists' , 'page_action_title' , 'page_title' ) );
    }

    /**
     * Creates a metric.
     *
     * @param SupplierRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store( LocationRequest $request , Location $location ) {
        $location->fill( Input::all() );
        $location->save();
        $helper = new Helper;
        $input = Input::except( '_token' , 'name' , 'description' );
        $helper->addMetaData( $input , $location );

        return Redirect::to( route( 'locations' ) )
                        ->with( 'flash_alert_notice' , 'Location was successfully created !' )->with( 'alert_class' , 'alert-success alert-dismissable' );
    }

    /**
     * Displays the form for editing the specified metric.
     *
     * @param int|string $id
     *
     * @return \Illuminate\View\View
     */
    public function edit( Location $location ) {

        $page_action_title = 'Edit location';
        $category = Location::find( $location->id );
        $category_lists = Location::lists( 'name' , 'id' );
        $page_title = 'Edit location - ' . $category[ 'name' ];
        // Custom edit field and meta data
        $helper = new Helper;
        $data = $helper->getCustomFields( 'Location' );
        $model = $location::find( $location->id );
        $custom_record = $model->getAllMeta();
        // End custom field edit and meta data

        return view( 'packages::location.edit' , compact( 'data' , 'custom_record' , 'location' , 'category_lists' , 'category' , 'page_action_title' , 'page_title' ) );
    }

    /**
     * Updates the specified metric.
     *
     * @param SupplierRequest $request
     * @param int|string    $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(
    LocationRequest $request , Location $location ) {
        $location->fill( Input::all() );
        $location->save();
        $helper = new Helper;
        $input = Input::except( '_token' , 'name' , 'description' );
        $helper->updateMetaData( $input , $location );

        return Redirect::to( route( 'locations' ) )
                        ->with( 'flash_alert_notice' , 'Location was successfully updated!' )->with( 'alert_class' , 'alert-success alert-dismissable' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy( Location $location ) {

        $category = Location::where( 'parent_id' , $location->id )->count();
        if ( $category == 0 ) {
            Location::destroy( $location->id );
            $msg = 'Location was successfully deleted!';
        }
        else {
            $msg = "You can't delete this location";
        }
        return Redirect::to( route( 'locations' ) )
                        ->with( 'flash_alert_title' , 'OK' )->with( 'flash_alert_notice' , $msg )->with( 'alert_class' , 'alert-success alert-dismissable' );
    }

}
