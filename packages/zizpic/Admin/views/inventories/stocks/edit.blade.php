@extends('layouts.master')
@section('content')
<div class="content-wrapper">
    @include('packages::inventories.stocks.grid.sectionhead')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        {!! Form::model($inventoryStock, ['method' => 'PATCH', 'route' => ['stocks.update', $inventory->id,$inventoryStock->id],'class'=>'form-horizontal']) !!}
                        @include('packages::inventories.stocks.form', compact('item','inventoryStock','btn_title','category_lists','metric_lists','inventory_lists'))
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
    </section>

</div>
@stop