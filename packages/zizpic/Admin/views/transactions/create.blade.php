@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    @include('partials.sectionhead')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div assas-sa='sa' class="box">
                    <div class="box-body">
                        {!! Form::open(array('id'=>'updateStocks','class' => 'form-inline pull-left', 'method' => 'POST',   'route' => array('transactions.create'))) !!}
                        @foreach($results as $key => $result)
                        {!! Form::hidden('stock_id[]',$result['stock_id'],['class'=>'stock_'.$result['stock_id']]) !!}
                        {!! Form::hidden('stock_quantity[]',$result['stock_quantity'],['class'=>'stock_'.$result['stock_id']]) !!}
                        {!! Form::hidden('stock_serial[]',$result['stock_serial'],['class'=>'stock_'.$result['stock_id']]) !!}
                        @endforeach
                        {!! Form::close() !!}
                        <a data-toggle="modal" data-target="#addStock" class="pull-right" href="#" data-toggle="tooltip" data-original-title="Add Stock">
                            <div class="btn btn-primary pull-right">
                                <span class="visible-xs-inline pull-right">Add Stock</span><i class="fa fa-plus"></i>
                            </div>
                        </a>
                        {!! Form::model($transaction, ['route' => ['transactions.store'],'class'=>'form-horizontal']) !!}
                        <div>
                            <table id='stockRecords' class='results table table-hover'>
                                <thead>
                                    <tr>
                                        <th>#</th><th>Stock ID</th><th>Stock Quantity</th><th>Stock S/N</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @include('packages::transactions.grid.results')
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group{{ $errors->first('state', ' has-error')}}">
                            <label class="col-sm-2 control-label">State</label>
                            <div class="col-md-4">
                                {!! Form::select('state', $state_lists, null, ['class'=>' form-control']) !!}
                                <span class="label label-danger">{{ $errors->first('state', ':message')}}</span>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->first('name', ' has-error')}}">
                            <label class="col-sm-2 control-label">Comment</label>
                            <div class="col-md-4">
                                {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'']) !!}
                                <span class="label label-danger">{{ $errors->first('name', ':message')}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                {!! Form::submit('Submit', array('class'=>'btn btn-primary add_transaction','id'=>'add_transaction')) !!}
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

@stop
