<?php

namespace Inventory\Admin\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\View;
use Input;
use Validator;
use Inventory\Admin\Http\Requests\MetricRequest;
use Inventory\Admin\Models\Metric;
use Auth;
use Paginate;
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

//use Inventory\Admin\Http\Requests\MetricRequest;

/**
 * Class MetricController
 */
class MetricController extends Controller {

    public function __construct() {
        $this->middleware( 'auth' );
        parent::__construct();
    }

    /**
     * @var MetricRepository
     */
    protected $metric;

    /**
     * Displays all metrics.
     *
     * @return \Illuminate\View\View
     */
    public function index() {

        $page_action_title = 'Metrics';
        $page_title = 'Metrics Management';
        $grid = $this->generateGrid( $page_action_title );
        $grid->setDataProvider(
                new EloquentDataProvider( Metric::query() )
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
                    ->setName( 'symbol' )
                    ->setLabel( 'Symbol' )
                    ->setSortable( true )
                    ->setCallback( function ($val) {
                                return $val;
                            } )
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

                                $html = '<a class="pull-left" href="' . route( 'metrics.edit' , $attr->id ) . '" >' . $btn . '</a>';
                                $html .= Form::open( array( 'class' => 'form-inline pull-left deletion-form' , 'method' => 'DELETE' , 'route' => array( 'metrics.destroy' , $attr->id ) ) );
                                $html .= Form::button( '' , array( 'class' => 'no-style fa fa-trash-o delete-Btn' , 'type' => 'submit' ) );
                                $html .= Form::close();
                                return $html;
                            } )
                ] );
                $grid = new Grid( $grid );
                $grid = $grid->render();
                $routes = ['create' => 'metrics.create' ];
                return $this->view( 'packages::metrics.index' , compact( 'routes' , 'grid' , 'page_action_title' , 'page_title' ) );
            }

            /**
             * Displays the form to create a metric.
             *
             * @return \Illuminate\View\View
             */
            public function create( Metric $metric ) {

                $page_action_title = 'Create Metric';
                $page_title = 'Create Metric';
                return view( 'packages::metrics.create' , compact( 'metric' , 'page_action_title' , 'page_title' ) );
            }

            /**
             * Creates a metric.
             *
             * @param MetricRequest $request
             *
             * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
             */
            public function store( MetricRequest $request , Metric $metric ) {
                $metric->fill( Input::all() );
                $metric->user_id = Auth::user()->id;
                $metric->save();
                return Redirect::to( route( 'metrics' ) )
                                ->with( 'flash_alert_notice' , 'Metrics was successfully created !' )->with( 'alert_class' , 'alert-success alert-dismissable' );
            }

            /**
             * Displays the form for editing the specified metric.
             *
             * @param int|string $id
             *
             * @return \Illuminate\View\View
             */
            public function edit( Metric $metric ) {

                $page_action_title = 'Edit Metric';
                $page_title = 'Edit Metric - ' . $metric->name;

                return view( 'packages::metrics.edit' , compact( 'metric' , 'url' , 'page_action_title' , 'page_title' ) );
            }

            /**
             * Updates the specified metric.
             *
             * @param MetricRequest $request
             * @param int|string    $id
             *
             * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
             */
            public function update( MetricRequest $request , Metric $metric ) {
                $metric->fill( Input::all() );
                $metric->save();
                return Redirect::to( route( 'metrics' ) )
                                ->with( 'flash_alert_notice' , 'Metrics was successfully updated !' )->with( 'alert_class' , 'alert-success alert-dismissable' );
            }

            /**
             * Remove the specified resource from storage.
             *
             * @param  int $id
             * @return Response
             */
            public function destroy( Metric $metric ) {
                Metric::destroy( $metric->id );
                return Redirect::to( route( 'metrics' ) )
                                ->with( 'flash_alert_title' , 'OK' )->with( 'flash_alert_notice' , 'Metric was successfully deleted!' )->with( 'alert_class' , 'alert-success alert-dismissable' );
            }

        }
