@extends('layouts.master')
@section('content')
<div class="content-wrapper">
    @include('packages::kits.grid.sectionhead')
    <section class="content">

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        @if($error_msg)
                        <div class="alert alert-danger">{{ $error_msg }}</div>
                        @else
                        <a  data-toggle="modal" data-target="#transaction"  class="pull-right" href="javascript::void(0);" data-toggle="tooltip" data-original-title="Create Stocks">
                            <div class="btn btn-primary pull-right"  >
                                Create Kit Transaction<i class="fa fa-plus"></i>
                            </div>
                        </a>
                        <table id="data-grid" class="results table table-hover" data-source="{{ url('/') }}" data-grid="main">
                            <thead>
                                <tr>
                                    <th class="sortable" data-sort="id">#</th>
                                    <th class="sortable" data-sort="name">Kit Type Name</th>
                                    <th class="sortable" data-sort="name">Kit Stock ID</th>
                                    <th class="sortable" data-sort="symbol">Quantity</th>
                                    <th class="sortable" data-sort="symbol">Kit Type S/N</th>
                                    <th class="sortable" data-sort="symbol">Created By</th>
                                    <th class="sortable" data-sort="user_id">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @include('packages::kits.grid.results')
                            </tbody>
                        </table>
                        @endif
                        <div align="center">
                            @if ($results->count()>0)
                            {!! $results->render() !!}
                            @endif</div>
                    </div>
                </div>
            </div>


    </section>
</div>

<!-- model -->

<div class="modal fade " id="transaction" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Transaction</h4>
            </div>
            <div class="modal-body">

                <form name="kitTransaction" id="kitTransaction"  class= "form-horizontal">
                    @include('packages::kits.kitsTransactionForm')
                </form>
            </div>
            <div class="modal-footer">


                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id='saveKitTransaction' >Save Transaction</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@stop



