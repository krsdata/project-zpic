
@foreach($results as $key => $result)
<tr>
    <td>{{ $result['inventory_id'] }}
        {!! Form::hidden('stock_inventory_id',$result['inventory_id']) !!}
    </td>
    <td>{{ $result['inventory_name'] }}</td>
    <td>
        @if ($result['stock_quantity'] > 1)
        {!! Form::number('stock_quantity[]',$result['stock_quantity'],['min'=>1,'max'=>$result['max_stock_quantity']]) !!}
        @else
        1
        {!! Form::hidden('stock_quantity[]',$result['stock_quantity']) !!}
        @endif
    </td>

    <td>{{ $result['stock_serial'] }} </td>
    <td> {!! Form::text('reason[]',$result['reason']) !!}</td>
    <td>
        {!! Form::hidden('stock_id[]',$result['stock_id']) !!}
        <button data-stock-id='{{ $result['stock_location_id'] }}' class="preventSubmit deleteStockRecord fa fa-trash-o"></button>
        {!! Form::hidden('location_id[]',$result['stock_location_id']) !!}
    </td>
</tr>
@endforeach