<tr id='splid_id_{{ $result->id }}' style="display:none; background:#f4f4f4">
    <td>#</td>
    <td>{{ ($result->serial_no!='0')?$result->serial_no:'N/A' }} </td>
    <td>{{ ucfirst($result->locationName['name']) }} </td>
    <td>
        {!! Form::model($inventoryStock, ['url' => 'inventory/stocks/split','class'=>'form-horizontal']) !!}
        {!! Form::hidden('inventory_id',$result->inventory_id) !!}
        {!! Form::hidden('stock_id',$result->id) !!}
        {!! Form::hidden('location_id',$result->location_id) !!}
        {!! Form::hidden('serial_no',$result->serial_no) !!}

        <div class="col-md-4">
            {!! Form::number('quantity',null, ['class'=>'form-control', 'placeholder'=>'','min'=>1,'max'=>($result->quantity-1)]) !!}
            <span class="label label-danger">{{ $errors->first('quantity', ':message') }}</span>
        </div>

        {!! Form::submit('Save', array('class' => 'btn btn-primary','id'=>'split_qty')) !!}
        {!! Form::close() !!}
    </td>
    <td>{{ $result->updated_at }}</td>
    <td>

    </td>
</tr>