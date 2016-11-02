@extends('layouts.master')
@section('content')
<div class="content-wrapper">
    @include('packages::kitstocks.grid.sectionhead')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <a href="{{url('inventory/kitstocks/create-kitstocks')}}" class="pull-right create-btn" data-toggle="tooltip" data-original-title="Create Kits">
                            <div class="btn btn-primary pull-right"  >
                                Create kits Stocks <i class="fa fa-plus"></i>
                            </div>
                        </a>
                        <table id="data-grid" class="results table table-hover" data-source="{{ url('/') }}" data-grid="main">
                            <thead>
                                <tr>
                                    <th class="sortable" data-sort="id">#</th>
                                    <th class="sortable" data-sort="name">Kit Name</th>
                                    <th class="sortable" data-sort="name">Stock Name</th>
                                    <th class="sortable" data-sort="symbol">Quantity</th>
                                    <th class="sortable" data-sort="user_id">Serial No</th>
                                    <th class="sortable" data-sort="user_id">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach($stocksKit['stockname'] as $key => $value)
                                <tr>
                                    <td>{{ $i++  }}</td>
                                    <td> {{ $stocksKit['kitname'][$key] }} </td>
                                    <td> {{ $stocksKit['stockname'][$key] }} </td>
                                    <td>{{ $stocksKit['quantity'][$key] }} </td>
                                    <td>{{ $stocksKit['serial_no'][$key] }} </td>

                                    <td>
                                        <a onclick="deleteRecord('{{ url('inventory/kitstocks/destroy/'.$stocksKit['id'][$key]) }}')" href="javascript::void(0)"><i class="fa fa-trash-o"></i></a>
                                        <a  href="{{ url('inventory/kitstocks/edit/'.$stocksKit['kit_stock_id'][$key]) }}">
                                            {!! Form::button('', array('class' => 'fa fa-pencil-square-o')) !!}
                                        </a>

                                    </td>
                                </tr>
                                @endforeach


                            </tbody>
                        </table>
                        <div align="center">
                            @if ($kitStockMapResults->count()>0)
                            {!! $kitStockMapResults->render() !!}
                            @endif</div>
                    </div>
                </div>
                </section>
            </div>
            @stop



