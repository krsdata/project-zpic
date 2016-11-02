@extends('layouts.master') 
@section('content')
<div class="content-wrapper">
@include('packages::kits.grid.sectionhead')
<section class="content">
  <div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                {!!
                    Form::open([
                        'url' => $url,
                        'class' => 'form-horizontal'
                    ])
                !!}
                
                @include('packages::kits.stockskitform')

                {!! Form::close() !!}  
            </div>
        </div>
    </div>
</section>

</div>
@stop