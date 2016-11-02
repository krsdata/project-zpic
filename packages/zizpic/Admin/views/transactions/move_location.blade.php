@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    @include('partials.sectionhead')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div assas-sa='sa' class="box">
                    <div class="box-body">
                        <input type='hidden' value='{{ route('transactions.create') }}' id='updateStocks' />
                        <!--{!! Form::open(array('id'=>'updateStocks','class' => 'form-inline pull-left', 'method' => 'POST',  'route' => array('transactions.create'))) !!}
                        @foreach($results as $key => $result)
                        {!! Form::hidden('stock_id[]',$result['stock_id'],['class'=>'stock_'.$result['stock_id']]) !!}
                        {!! Form::hidden('stock_quantity[]',$result['stock_quantity'],['class'=>'stock_'.$result['stock_id']]) !!}
                        {!! Form::hidden('stock_location_id[]',$result['stock_location_id'],['class'=>'stock_'.$result['stock_id']]) !!}
                        {!! Form::hidden('stock_serial[]',$result['stock_serial'],['class'=>'stock_'.$result['stock_id']]) !!}
                        @endforeach
                        {!! Form::close() !!}--!
                        <!--                        <a data-toggle="modal" data-target="#addStock" class="pull-right" href="#" data-toggle="tooltip" data-original-title="Add Stock">
                                                    <div class="btn btn-primary pull-right">
                                                        <span class="visible-xs-inline pull-right">Add Stock</span><i class="fa fa-plus"></i>
                                                    </div>
                                                </a> -->
                        <a data-toggle="modal" data-target="#addLocation" class="pull-right" href="#" data-toggle="tooltip" data-original-title="Add Location">
                            <div class="btn btn-primary pull-right">
                                <span class="visible-xs-inline pull-right">Add Stock</span><i class="fa fa-plus"></i>
                            </div>
                        </a>

                        {!! Form::model($transaction, ['route' => ['transactions.store'],'class'=>'form-horizontal','id' => 'location_transaction']) !!}
                        <div>
                            <table id='stockRecords' class='results table table-hover'>
                                <thead>
                                    <tr>
                                        <th>#</th><th>Item Name</th><th>Stock Quantity</th><th>Stock S/N</th><th>Comments</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @include('packages::transactions.grid.results')
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group{{ $errors->first('location', ' has-error')}}">
                            <label class="col-sm-2 control-label ">New Location</label>
                            <div class="col-md-4">
                                {!! Form::select('new_location', $location_lists, (Input::has('new_location') ? Input::get('new_location') : ''), ['class'=>' form-control']) !!}
                                <span class="label label-danger location">{{ $errors->first('location', ':message')}}</span>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->first('name', ' has-error')}}">
                            <label class="col-sm-2 control-label">Comment</label>
                            <div class="col-md-4">
                                {!! Form::text('name', (Input::has('name') ? Input::get('name') : ''), ['class'=>'form-control', 'placeholder'=>'']) !!}
                                <span class="label label-danger comment">{{ $errors->first('name', ':message')}}</span>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->first('no_of_boxes', ' has-error')}}">
                            <label class="col-sm-2 control-label">Amount of boxes</label>
                            <div class="col-md-4">
                                {!! Form::text('no_of_boxes', (Input::has('no_of_boxes') ? Input::get('no_of_boxes') : ''), ['class'=>'form-control', 'placeholder'=>'Amount of boxes']) !!}
                                <span class="label label-danger no_of_boxes">{{ $errors->first('no_of_boxes', ':message')}}</span>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->first('weight_of_shipment', ' has-error')}}">
                            <label class="col-sm-2 control-label">Weight of shipment (KG)</label>
                            <div class="col-md-4">
                                {!! Form::text('weight_of_shipment', (Input::has('weight_of_shipment') ? Input::get('weight_of_shipment') : ''), ['class'=>'form-control', 'placeholder'=>'Weight of shipment']) !!}
                                <span class="label label-danger weight_of_shipment">{{ $errors->first('weight_of_shipment', ':message')}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                {!! Form::submit('Move stock', array('class'=>'btn btn-primary add_transaction','id'=>'add_transaction')) !!}

                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
    </section>
</div>
<div id="addStock" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Stock</h4>
            </div>
            <div class="modal-body">
                {!! Form::select('inventory', $inventory_lists, null, ['class'=>' form-control']) !!}
                <table class='results table table-hover' id='stock_list'>
                    <thead>
                    <th>Stock ID</th>
                    <th>Stock Quantity</th>
                    <th>Stock S/N</th>
                    <th>Actions</th>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="addLocation" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Stock</h4>
            </div>
            <div class="modal-body">
                <form id="search-form">
                    {!! Form::select('inventory_id',[null=>'Please Select'] +  $inventory_lists, null, ['class'=>' form-control','onChange'=>'$(this).submit()']) !!}
                </form>
                <table class="table table-striped table-bordered dataTable" id="stock-by-location">
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@stop
