@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    @include('partials.sectionhead')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        {!! Form::model($metric, ['method' => 'PATCH', 'route' => ['metrics.update', $metric->id],'class'=>'form-horizontal']) !!}
                        @include('packages::metrics.form', compact('metric'))
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
    </section>
</div>
@stop


