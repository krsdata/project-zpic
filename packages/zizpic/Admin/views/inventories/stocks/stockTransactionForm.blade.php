
<tr id='create_trans_{{ $result->id }}' style="display:none; background:#f4f4f4" >
    <td></td>
    <td>{!! Form::model($inventoryStock, ['route' => ['stocks.store'],'class'=>'form-horizontal']) !!}
    </td>
    <td>
        <div class="col-lg-10">
            {!! Form::select('state', $state_lists, null, ['class'=>' form-control']) !!}
            <span class="label label-danger">{{ $errors->first('state', ':message') }}</span>
        </div>
    </td>
    <td>
        {!! Form::hidden('create_trans','create_trans') !!}
        {!! Form::hidden('location_id',$result->location_id) !!}
        {!! Form::hidden('inventory_id',$result->inventory_id) !!}
        {!! Form::hidden('trans_stock_id',$result->id) !!}
        {!! Form::hidden('quantity',$result->quantity) !!}

        <div class="col-md-4">
            {!! Form::textarea('name',null, ['placeholder'=>'Reason','size' => '30x3']) !!}
            <span class="label label-danger">{{ $errors->first('quantity', ':message') }}</span>
        </div>


    </td>
    <td>
        {!! Form::submit('Save', array('class' => 'btn btn-primary','id'=>'split_qty')) !!}
        {!! Form::close() !!}
    </td>
    <td>

    </td>
</tr>