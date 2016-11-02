@extends('layouts.master')
@section('content')
<div class="content-wrapper">
    @include('partials.sectionhead')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        @if($count==0)
                        <div class="alert alert-danger ">Stock is not associated </div>
                        @else

                        {!!
                        Form::open([
                        'url' => $url,
                        'class' => 'form-horizontal'
                        ])
                        !!}

                        @include('packages::kits.form')

                        {!! Form::close() !!}
                        @endif
                    </div>
                </div>
            </div>
    </section>

</div>
@stop