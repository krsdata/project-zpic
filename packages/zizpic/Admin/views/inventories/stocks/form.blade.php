{!! Form::hidden('inventory_id',$inventory_id) !!}
@if (!$inventoryStock->id)
<div class="form-group{{ $errors->first('location_id', ' has-error') }}">
    <label class="col-sm-2 control-label">Location</label>
    <div class="col-md-4">
        {!! Form::select('location_id', [null=>'Please Select'] + $category_lists, (isset($item->location_id) ? $item->location_id : null), ['class'=>'form-control']) !!}
        <span class="label label-danger">{{ $errors->first('name', ':message') }}</span>
        <span class="label label-danger">{{ $errors->first('location_id', ':message') }}</span>
    </div>
</div>
@else
{!! Form::hidden('location_id', null) !!}
@endif
@if($inventory->is_serialno == 1)
<div class="form-group{{ $errors->first('serial_no', ' has-error') }}">
    <label class="col-sm-2 control-label">Serial no.</label>

    <div class="col-md-4">
        {!! Form::text('serial_no', null, ['class'=>'form-control', 'placeholder'=>'Serial no']) !!}

        <span class="label label-danger">{{ $errors->first('serial_no', ':message') }}</span>
    </div>
</div>
@endif
<div class="form-group{{ $errors->first('quantity', ' has-error') }}">
    <label class="col-sm-2 control-label">Quantity</label>
    <div class="col-md-4">
        @if(($inventory->is_serialno == 0 && $inventory->is_assembly == 0) && !$inventoryStock->id)
        {!! Form::text('quantity', NULL, ['class'=>'form-control', 'placeholder'=>'Quantity']) !!}
        @else
        {!! Form::hidden('quantity', (isset($item) ? $item->quantity :1)) !!}
        <span style="line-height: 36px;">{{ (isset($item) ? $item->quantity :1) }}</span>
        @endif
        <span class="label label-danger">{{ $errors->first('quantity', ':message') }}</span>
    </div>
</div>
<div class="form-group{{ $errors->first('aisle', ' has-error') }}">
    <label class="col-sm-2 control-label">Aisle</label>

    <div class="col-md-4">
        {!! Form::text('aisle',null, ['class'=>'form-control', 'placeholder'=>'Aisle']) !!}

        <span class="label label-danger">{{ $errors->first('aisle', ':message') }}</span>
    </div>
</div>
<div class="form-group{{ $errors->first('row', ' has-error') }}">
    <label class="col-sm-2 control-label">Row</label>

    <div class="col-md-4">
        {!! Form::text('row', null, ['class'=>'form-control', 'placeholder'=>'Row']) !!}

        <span class="label label-danger">{{ $errors->first('row', ':message') }}</span>
    </div>
</div>
<div class="form-group{{ $errors->first('bin', ' has-error') }}">
    <label class="col-sm-2 control-label">Bin</label>
    <div class="col-md-4">
        {!! Form::text('bin', null, ['class'=>'form-control', 'placeholder'=>'Bin']) !!}
        <span class="label label-danger">{{ $errors->first('bin', ':message') }}</span>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
    </div>
</div>