@extends('layouts.master')
@section('content')
<div class="content-wrapper">
    @include('packages::inventories.stocks.grid.sectionhead')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        {!! Form::model($inventoryStock, ['route' => ['stocks.store',$inventory->id],'class'=>'form-horizontal']) !!}

                        @include('packages::inventories.stocks.form',compact('inventoryStock'))

                        {!! Form::hidden('is_assembly',$is_assembly) !!}

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
    </section>

</div>
@stop