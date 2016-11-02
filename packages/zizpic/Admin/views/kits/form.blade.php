{!! Form::hidden('kit_id', $kit_id) !!}
{!! Form::hidden('total_kit_qty',$total_kit_qty,['id'=>'total_kit_qty']) !!}
{!! Form::hidden('kit_stock_id',$id) !!}
<div class="danger alert-danger alert-dismissable  " id="error_msg"></div>
<div class="accordion-group">

    @foreach ( $stock_list as $key => $value )
    <div class="accordion-heading">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion{{$id}}" href="#collapseTwo{{$id}}">
            {{ $key }} : (Max Stock Quantity allowed  {{ $qty[$offset] }})
        </a>
    </div>
    <div id="collapseTwo{{$id++}}" class="accordion-body collapse">

        <div class="accordion-inner">
            <table id="data-grid" class="results table table-hover"  data-grid="main">
                <thead>
                    <tr>
                        <th> Stock ID</th><th>Stock S/N</th><th>Stock Location</th><th>Stock Quantity</th><th>Action</th>
                    </tr>
                </thead>
                {!! Form::hidden('max_qty[]',$qty[ $offset ]) !!}
                @foreach ( $value as $key => $stock )
                <tr>
                    {!! Form::hidden('kit_stock_map_id_'.$stock[ 'stock_id' ],isset($kitmap_record[ $key]['id'])?$kitmap_record[ $key]['id']:'') !!}

                    <th>{{ $stock[ 'stock_id' ] }}</th>
                    <th>
                        {{ ($stock[ 'serial_no' ])?$stock[ 'serial_no' ]:'N/A' }}
                        {!! Form::hidden('serial_no_'.$stock[ 'stock_id' ],($stock[ 'serial_no' ])?$stock[ 'serial_no' ]:'N/A') !!}
                    </th>
                    <th>{{ $location_name['name'] }}</th>
                    <th>
                        {!! Form::hidden('quantity_'.$stock[ 'stock_id' ], $stock[ 'stock_quantity' ]) !!}
                        {{ $stock[ 'stock_quantity' ] }}
                    </th><td>
                        {!! Form::checkbox('inventory_id_'.$offset.'[]', $stock[ 'stock_id' ].":".$stock['stock_quantity'],(isset($is_checked) && in_array( $stock[ 'stock_id' ] ,$is_checked))?'checked':false ,    ['qty'=>$stock['stock_quantity' ] ,'class'=>'chk','id'=>$stock[ 'stock_id' ],  ($stock[ 'stock_quantity' ]>$stock['max_quantity'])?'disabled':false ] ) !!}
                    </td>
                </tr>
                @endforeach
            </table >
        </div>
    </div>
    <div style="display: none" id='{{  $offset++ }}' ></div>
    @endforeach

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            @if(count( $arr )>0)
            {!! Form::submit('Save', ['class'=>'btn btn-primary','id'=>'save_kit'] ) !!}
            <input type="button" class="btn btn-primary" value="Back" onclick="return window.history.back();">
            @else

            <input type="button" class="btn btn-primary" value="Back" onclick="return window.history.back();">
            @endif
        </div>
    </div>


