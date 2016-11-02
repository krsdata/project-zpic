<?php

namespace Inventory\Admin\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\View;
use Input;
use Validator;
use Inventory\Admin\Models\InventoryTransactionState;
use Inventory\Admin\Http\Requests\TransactionStateRequest;
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
class InventoryTransactionStateController extends Controller {

    public function __construct() {
        $this->middleware( 'auth' );
        parent::__construct();
    }

    /**
     * @var SupplierRepository
     */
    protected $locations;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Displays all metrics.
     *
     * @return \Illuminate\View\View
     */
    public function index() {

        $page_action_title = 'Transaction States';
        $page_title = 'Transaction State Management';
        $grid = new Grid(
                (new GridConfig )
                        ->setDataProvider(
                                new EloquentDataProvider( InventoryTransactionState::query() )
                        )
                        ->setName( str_slug( $page_action_title ) )
                        ->setPageSize( Config::get( 'app.NumberOfPerPage' ) )
                        ->setColumns( [
                            (new FieldConfig )
                            ->setName( 'id' )
                            ->setLabel( 'ID' )
                            ->setSortable( true )
                            ->setSorting( Grid::SORT_ASC )
                            ,
                            (new FieldConfig )
                            ->setName( 'state' )
                            ->setLabel( 'State' )
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

                                        $html = '<a class="pull-left" href="' . route( 'transactionstates.edit' , $attr->id ) . '" >' . $btn . '</a>';
                                        $html .= Form::open( array( 'class' => 'form-inline pull-left deletion-form' , 'method' => 'DELETE' , 'route' => array( 'transactionstates.destroy' , $attr->id ) ) );
                                        $html .= Form::button( '' , array( 'class' => 'no-style fa fa-trash-o delete-Btn' , 'type' => 'submit' ) );
                                        $html .= Form::close();
                                        return $html;
                                    } )
                                ] )
                                ->setComponents( [
                                    (new THead )
                                    ->setComponents( [
                                        (new OneCellRow )
                                        ->setComponents( [
                                            new ColumnsHider ,
                                            (new CsvExport )
                                            ->setFileName( str_slug( $page_action_title ) . '_' . date( 'Y-m-d-H-m-s' ) . '_' . time() ) ,
                                        ] ) ,
                                        new FiltersRow ,
                                        new RenderFunc( function() {
                                            return '</form>';
                                        } ) ,
                                        (new ColumnHeadersRow) ,
                                    ] )
                                    ,
                                    (new TFoot )
                                    ->setComponents( [
                                        (new OneCellRow )
                                        ->setComponents( [
                                            new Pager ,
                                            (new HtmlTag )
                                            ->setAttributes( [ 'class' => 'pull-right' ] )
                                            ->addComponent( new ShowingRecords ) ,
                                        ] )
                                    ] ) ,
                                ] )
                );

                $grid = $grid->render();
                return view( 'packages::transactionstates.index' , compact( 'grid' , 'page_action_title' , 'page_title' ) );
            }

            /**
             * Displays the form to create a metric.
             *
             * @return \Illuminate\View\View
             */
            public function create( InventoryTransactionState $transactionState ) {
                $page_action_title = 'Create Transaction State';
                $page_title = $page_action_title;
                return view( 'packages::transactionstates.create' , compact( 'transactionState' , 'page_action_title' , 'page_title' ) );
            }

            /**
             * Creates a metric.
             *
             * @param SupplierRequest $request
             *
             * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
             */
            public function store( TransactionStateRequest $request , InventoryTransactionState $transactionState ) {
                $transactionState->fill( Input::all() );
                $transactionState->save();
                return Redirect::to( route( 'transactionstates' ) )
                                ->with( 'flash_alert_notice' , 'Transaction State was successfully created !' )->with( 'alert_class' , 'alert-success alert-dismissable' );
            }

            /**
             * Displays the form for editing the specified metric.
             *
             * @param int|string $id
             *
             * @return \Illuminate\View\View
             */
            public function edit( InventoryTransactionState $transactionState ) {


                $page_action_title = 'Edit Transaction state - ' . $transactionState->state;
                $page_title = $page_action_title;
                return view( 'packages::transactionstates.edit' , compact( 'transactionState' , 'page_action_title' , 'page_title' ) );
            }

            /**
             * Updates the specified metric.
             *
             * @param SupplierRequest $request
             * @param int|string    $id
             *
             * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
             */
            public function update( TransactionStateRequest $request , InventoryTransactionState $transactionState ) {
                $transactionState->fill( Input::all() );
                $transactionState->save();
                return Redirect::to( route( 'transactionstates' ) )
                                ->with( 'flash_alert_notice' , 'Transaction State was successfully updated!' )->with( 'alert_class' , 'alert-success alert-dismissable' );
            }

            /**
             * Remove the specified resource from storage.
             *
             * @param  int $id
             * @return Response
             */
            public function destroy( InventoryTransactionState $transactionState ) {

                InventoryTransactionState::destroy( $transactionState->id );
                return Redirect::to( route( 'transactionstates' ) )
                                ->with( 'flash_alert_title' , 'OK' )->with( 'flash_alert_notice' , 'Transaction State was successfully deleted!' )->with( 'alert_class' , 'alert-success alert-dismissable' );
            }

        }
