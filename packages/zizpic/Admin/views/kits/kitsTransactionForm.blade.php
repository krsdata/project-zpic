<table id="data-grid" class="results table table-hover" data-source="{{ url('/') }}" data-grid="main">
    <thead>
        <tr>
            <td>Inventory Name</td>
            <td>State</td>
            <td>Name</td>
            <td>Quantity</td>
        </tr>
    </thead>
    @foreach($results as $key => $result)
    <tr>
        <td>{{ $result->inventoryName['name'] }}</td>
        <td>
            {!! Form::hidden('create_kit_trans','create_kit_trans') !!}
            {!! Form::hidden('kit_id',$kit_id) !!}
            {!! Form::hidden('inventory_id[]',$result->inventoryName['id']) !!}
            <div class="form-group{{ $errors->first('state_lists', ' has-error') }}">

                <div class="col-md-12">
                    {!! Form::select('state[]', [null=>'Select State']+$state_lists , null, ['class'=>'form-control state_id','id'=>'state']) !!}
                    <span class="label label-danger state"></span>
                </div>
            </div>
        </td>
        <td>
            <div class="form-group{{ $errors->first('name', ' has-error') }}">

                <div class="col-md-4">
                    {!! Form::textarea('name[]',null, ['placeholder'=>'Reason','size' => '30x3','id'=>'name']) !!}
                    <span class="label label-danger name">{{ $errors->first('name', ':message') }}</span>
                </div>
            </div>
        </td>
        <td>
            <div class="form-group{{ $errors->first('state_lists', ' has-error') }}">

                <div class="col-md-4">
                    {{ $result->quantity }}
                    {!! Form::hidden('quantity[]',$result->quantity, ['placeholder'=>'quantity','id'=>'quantity','min'=>1]) !!}
                </div>
            </div>
        </td>
    </tr>
    @endforeach
</table>
