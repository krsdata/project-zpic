<?php

namespace Inventory\Admin\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\View;
use Input;
use Validator;
use Inventory\Admin\Models\Supplier;
use Inventory\Admin\Http\Requests\SupplierRequest;
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

/**
 * Class SupplierController
 */
class SupplierController extends Controller {

    public function __construct() {
        $this->middleware( 'auth' );
        parent::__construct();
    }

    /**
     * @var SupplierRepository
     */
    protected $supplier;

    /**
     * Displays all metrics.
     *
     * @return \Illuminate\View\View
     */
    public function index() {

        $page_action_title = 'Suppliers';
        $page_title = 'Suppliers Management';
        $grid = $this->generateGrid( $page_action_title );
        $grid->setDataProvider(
                new EloquentDataProvider( Supplier::query() )
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
                    ) ,
                    (new FieldConfig )
                    ->setName( 'country' )
                    ->setLabel( 'Country' )
                    ->setCallback( function ($val) {
                                return $val;
                            } )
                    ->setSortable( true )
                    ->addFilter(
                            (new FilterConfig )
                            ->setOperator( FilterConfig::OPERATOR_LIKE )
                    ) ,
                    (new FieldConfig )
                    ->setName( 'email' )
                    ->setLabel( 'Email' )
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
                                $btn = Form::button( '' , array( 'class' => 'no-style fa fa-pencil-square-o' ) );

                                $html = '<a class="pull-left" href="' . route( 'suppliers.edit' , $attr->id ) . '" >' . $btn . '</a>';
                                $html .= Form::open( array( 'class' => 'form-inline pull-left deletion-form' , 'method' => 'DELETE' , 'route' => array( 'suppliers.destroy' , $attr->id ) ) );
                                $html .= Form::button( '' , array( 'class' => 'no-style fa fa-trash-o delete-Btn' , 'type' => 'submit' ) );
                                $html .= Form::close();
                                return $html;
                            } )
                ] );
                $grid = new Grid( $grid );
                $grid = $grid->render();
                $routes = ['create' => 'suppliers.create' ];
                return $this->view( 'packages::locations.index' , compact( 'routes' , 'grid' , 'page_action_title' , 'page_title' ) );
            }

            /**
             * Displays the form to create a metric.
             *
             * @return \Illuminate\View\View
             */
            public function create( Supplier $supplier ) {

                $page_action_title = 'Create Supplier';
                $page_title = 'Create Supplier';
                $btn_title = 'Save';
                $url = 'inventory/suppliers/store';
                return view( 'packages::suppliers.create' , compact( 'supplier' , 'page_action_title' , 'page_title' , 'url' , 'btn_title' ) );
            }

            /**
             * Creates a metric.
             *
             * @param SupplierRequest $request
             *
             * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
             */
            public function store( SupplierRequest $request , Supplier $supplier ) {
                $supplier->fill( Input::all() );
                $supplier->save();

                return Redirect::to( route( 'suppliers' ) )
                                ->with( 'flash_alert_notice' , 'Supplier was successfully created !' )->with( 'alert_class' , 'alert-success alert-dismissable' );
            }

            /**
             * Displays the form for editing the specified supplier.
             *
             * @param int|string $id
             *
             * @return \Illuminate\View\View
             */
            public function edit( Supplier $supplier ) {


                $page_action_title = 'Edit supplier - ' . $supplier->name;
                $page_title = 'Supplier Management';
                $btn_title = 'Update';

                return view( 'packages::suppliers.edit' , compact( 'supplier' , 'url' , 'page_action_title' , 'page_title' , 'btn_title' ) );
            }

            /**
             * Updates the specified metric.
             *
             * @param SupplierRequest $request
             * @param int|string    $id
             *
             * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
             */
            public function update( SupplierRequest $request , Supplier $supplier ) {
                $supplier->fill( Input::all() );
                $supplier->save();
                return Redirect::to( route( 'suppliers' ) )
                                ->with( 'flash_alert_notice' , 'Suppliers was successfully updated !' )->with( 'alert_class' , 'alert-success alert-dismissable' );
            }

            /**
             * Displays the form for editing the specified metric.
             *
             * @param int|string $id
             *
             * @return \Illuminate\View\View
             */
            public function show( $id ) {

                $page_action_title = 'View Supplier Details';
                $page_title = 'Supplier Management';
                $supplier = $id;

                return view( 'packages::suppliers.show' , compact( 'supplier' , 'page_action_title' , 'page_title' ) );
            }

            /**
             * Remove the specified resource from storage.
             *
             * @param  int $id
             * @return Response
             */
            public function destroy( Supplier $supplier ) {
                Supplier::destroy( $supplier->id );
                return Redirect::to( '/inventory/suppliers' )
                                ->with( 'flash_alert_title' , 'OK' )->with( 'flash_alert_notice' , 'Supplier was successfully deleted!' )->with( 'alert_class' , 'alert-success alert-dismissable' );
            }

        }
