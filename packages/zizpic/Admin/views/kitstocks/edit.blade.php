@extends('layouts.master')
@section('content')
<div class="content-wrapper">
    @include('partials.sectionhead')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        {!!
                        Form::open([
                        'url' => $url,
                        'class' => 'form-horizontal',
                        'id' => 'kitstock'
                        ])
                        !!}

                        @include('packages::kitstocks.form', compact('item','btn_title','category_lists','metric_lists','inventory_lists'))

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
    </section>

</div>
@stop