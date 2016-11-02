@foreach($results as $key => $result)
<tr>
    <td>{{ ++$key }}</td>
    <td>{{ ($result->serial_no)?$result->serial_no:'N/A' }} </td>
    <td>{{ ucfirst($result->location['name']) }} </td>
    <td>{{ $result->quantity }}

        @if($result->quantity>1 &&  ($helper->check_stock_in_kit($result->id)==0))
        <a href="#" id="{{ $result->id }}" class="btn btn-primary btn-xs" onclick="splitStocks({{ $result->id }})" >Split </a>
        @endif
    </td>
    <td>{{ $result->updated_at }}</td>
    <td>
        @if($helper->check_stock_in_kit($result->id)==0)
        <!--<a href="{{ route('stocks.edit',[$result->inventory_id,$result->id])  }}" >
            {!! Form::button('', array('class' => 'fa fa-pencil-square-o')) !!}
        </a>-->
        <!--        {!! Form::open(array('class' => 'form-inline pull-left', 'method' => 'POST',   'route' => array('transactions.create'))) !!}
                {!! Form::hidden('stock_id[]',$result->id) !!}
                {!! Form::hidden('stock_quantity[]',$result->quantity) !!}
                {!! Form::hidden('stock_serial[]',$result->serial_no) !!}
                {!! Form::button('Create Transaction', array('class' => 'btn btn-primary btn-xs','type'=>'submit')) !!}
                {!! Form::close() !!}-->

        <!--{!! Form::open(array('class' => 'form-inline pull-left deletion-form', 'method' => 'DELETE',  'id'=>'deleteForm_'.$result->id, 'route' => array('stocks.destroy', $result->id))) !!}
        {!! Form::hidden('inventory_id',$result->inventory_id) !!}
        {!! Form::button('', array('class' => 'no-style fa fa-trash-o delete-Btn ','id'=>$result->id)) !!}
        {!! Form::close() !!}-->
        {!! Form::open(array('class' => 'form-inline pull-left', 'method' => 'POST',   'route' => array('transactions.create'))) !!}
        {!! Form::hidden('stock_id[]',$result->id) !!}
        {!! Form::hidden('stock_location_id[]',$result->location_id) !!}
        {!! Form::hidden('stock_quantity[]',$result->quantity) !!}
        {!! Form::hidden('stock_serial[]',$result->serial_no) !!}
        {!! Form::button('Move location', array('class' => 'btn btn-primary btn-xs','type'=>'submit')) !!}
        {!! Form::close() !!}
        @else
        {{ 'Stock attached to kit' }}
        @endif
    </td>
</tr>
@include('packages::inventories.stocks.splitStockForm')
@endforeach