@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    @include('partials.sectionhead')
    <section class="content">
        @if ( $errors->count() > 0 )
        <div class="alert alert-danger">
            <ul>
                @foreach( $errors->all() as $message )</p>
                <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">

                        {!!
                        Form::model($supplier,
                        ['method' => 'PATCH',
                        'route' => ['suppliers.update',
                        $supplier->id],
                        'class'=>'form-horizontal'])
                        !!}

                        @include('packages::suppliers.form', compact('supplier'))
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
    </section>
</div>
@stop


