<div class="form-group{{ $errors->first('kit_stock_id', ' has-error') }}">
    <label class="col-sm-2 control-label">Select Kit Name</label>

    <div class="col-md-4">
        <select name="kit_stock_id" class="form-control" onchange="getStockList(this.value,'{{ url("inventory/kitstocks/create-kitstocks/") }}')">
            <option value="">Select KIT Name</option>
            @foreach ($kitname_lists as $key => $value)
            <option value="{{ $key }}" @if($key==$id) {{ "selected" }} @endif>{{ $value }}</option>
            @endforeach
        </select>

        <span class="label label-danger">{{ $errors->first('kit_stock_id', ':message') }}</span>
    </div>
</div>

<div class="form-group{{ $errors->first('serial_no', ' has-error') }}">
    <label class="col-sm-2 control-label">Serial No</label>

    <div class="col-md-4">
        {!! Form::text('serial_no', 'SNO'.$id, ['class'=>'col-md-5', 'placeholder'=>'serial_no','readonly'=>'readonly']) !!}

        <span class="label label-danger">{{ $errors->first('serial_no', ':message') }}</span>
    </div>
</div>


<table id="data-grid" class="results table table-hover"  data-grid="main">
    <thead>
        <tr>
            <th class="sortable" >Kit Stocks Name</th>
            <th class="sortable" >Kit Stocks Details</th>
            <th class="sortable" >Serial no</th>
            <th class="sortable" >Quantity</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($kitResults as $key => $value)
        @foreach ($value->inventoryStockRelation as $key => $inventory_stocks)
        <tr>
            <th>{!! Form::checkbox('inventory_id[]', $inventory_stocks['id'] , (isset($kitStocksResult[$key]) && $kitStocksResult[$key]->stock_id==$inventory_stocks['id'])?'checked':false, ['id'=>$inventory_stocks['id']] ) !!}
                {!! $value->inventoryName['name'] !!}
            </th>
            <th class="sortable" >
                {{isset($inventory_stocks['aisle'])?'Aisle:'. $inventory_stocks['aisle'].',':''}}
                {{isset($inventory_stocks['bin'])?'Bin:'. $inventory_stocks['bin'].',':''}}
                {{isset($inventory_stocks['row'])?'Row:'. $inventory_stocks['row']:''}}
            </th>
            <th>
                {{   ($inventory_stocks['serial_no']=='0')?'N/A':$inventory_stocks['serial_no'] }}
            </th>
            <th>
                @if($value->inventoryName['is_serialno']==0)
                {!! Form::text('quantity[]', (isset($kitStocksResult[$key]))?$kitStocksResult[$key]->quantity:null, ['class'=>'col-md-5', 'placeholder'=>'quantity','data'=>$inventory_stocks['quantity'],'id'=>$inventory_stocks['id']]) !!}
                <span id="error_{{ $inventory_stocks['id'] }}"></span>
                Max quantity allowed ({{ $inventory_stocks['quantity'] }})
                @else
                {!! Form::hidden('quantity[]', 1,array('id'=>$inventory_stocks['id'])) !!}
                <span style="line-height: 36px;">1</span>
                @endif
            </th>
        </tr>
        @endforeach
        @endforeach
    </tbody>
</table>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit('Save', ['class'=>'btn btn-primary','id'=>'savestock']) !!}
        <input type="button" class="btn btn-primary" value="Back" onclick="return window.history.back();">
    </div>
</div>
