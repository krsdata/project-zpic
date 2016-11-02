@extends('layouts.master')
@section('content')
<div class="content-wrapper">
    @include('partials.sectionhead')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <a  class="pull-right" href="{{route('stocks.create',$inventory->id)}}" data-toggle="tooltip" data-original-title="Create Stocks">
                            <div class="btn btn-primary pull-right"  >
                                <span class="visible-xs-inline pull-right">Create Stocks</span><i class="fa fa-plus"></i>
                            </div>
                        </a>
                        <table id="data-grid" class="results table table-hover" data-source="{{ url('/') }}" data-grid="main">
                            <thead>
                                <tr>
                                  <!-- <th><input data-grid-checkbox="all" type="checkbox"></th> -->
                                    <th class="sortable" data-sort="id">#</th>
                                    <th class="sortable" data-sort="name">Serial no</th>
                                    <th class="sortable" data-sort="name">Location</th>
                                    <th class="sortable" data-sort="symbol">Quantity</th>
                                    <th class="sortable" data-sort="symbol">Updated At</th>
                                    <th class="sortable" data-sort="user_id">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @include('packages::inventories.stocks.grid.results')
                            </tbody>
                        </table>
                    </div>
                </div>
                </section>
            </div>
            @stop



