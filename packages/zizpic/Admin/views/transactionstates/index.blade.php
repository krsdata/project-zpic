

@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    @include('partials.sectionhead')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <a href="{{route('transactionstates.create')}}" data-toggle="tooltip" data-original-title="Create">
                            <div class="btn btn-primary pull-right"  >
                                <i class="fa fa-plus"></i> <span class="visible-xs-inline">Create</span>
                            </div>
                        </a>
                        @include('partials.grid')
                    </div>
                </div>
            </div>
    </section>
</div>
@stop

