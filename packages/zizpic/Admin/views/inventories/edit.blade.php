@extends('layouts.master')
@section('content')
<div id="inventory_form" class="content-wrapper">
    @include('partials.sectionhead')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        {!! Form::model($inventory, ['method' => 'PATCH', 'route' => ['inventories.update', $inventory->id],'class'=>'form-horizontal']) !!}

                        @include('packages::inventories.form', compact('item','inventory','btn_title','category_lists','metric_lists','inventory_lists'))

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
    </section>

</div>
@stop