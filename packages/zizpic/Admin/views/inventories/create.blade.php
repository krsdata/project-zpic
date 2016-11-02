@extends('layouts.master')
@section('content')
<div id="inventory_form" class="content-wrapper">
    @include('partials.sectionhead')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        {!! Form::model($inventory, ['route' => ['inventories.store'],'class'=>'form-horizontal preventSubmitOnEnter']) !!}
                        @include('packages::inventories.form', compact('inventory'))


                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
    </section>

</div>
@stop