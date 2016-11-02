@foreach($results as $key => $result)

<tr>
    <!-- <td>
        <input data-grid-checkbox type="checkbox" name="entries[]" value="<%= r.id %>">
    </td> -->
    <td>{{ ++$key }}</td>
    <td>{{ $result->inventory[ 'name' ] }}</td>
    <td>{{ $result['id'] }} </td>
    <td>{{ $result['quantity'] }} </td>
    <td> {{ ($result['serial_no'])?$result['serial_no']:'N/A' }} </td>
    <td>{{ Auth::user()->name }} </td>
    <td>
        <a href="{{ url('inventory/kits/edit/'.$result['id']) }}"><i class=" fa fa-pencil-square-o"></i> </a>
<!--        <a onclick="deleteRecord('{{ url('inventory/kits/destroy/'.$result->id) }}')" href="javascript::void(0)"><i class="fa fa-trash-o"></i></a>-->


    </td>
</tr>

@endforeach

