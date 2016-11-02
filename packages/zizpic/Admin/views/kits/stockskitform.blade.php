<!-- <div class="form-group{{ $errors->first('parent_id', ' has-error') }}">
    <label class="col-sm-2 control-label">Kit Name</label>

    <div class="col-md-4">
        {!! Form::select('kit_stock_id', [null=>'Please Select'] + $kitname_lists, (isset($item->parent_id) ? $item->parent_id : null), ['class'=>'form-control']) !!}
        <span class="label label-danger">{{ $errors->first('parent_id', ':message') }}</span>
    </div>
</div>

<div class="form-group{{ $errors->first('parent_id', ' has-error') }}">
    <label class="col-sm-2 control-label">Inventory Name</label>

    <div class="col-md-4">
        {!! Form::select('kit_stock_id', [null=>'Please Select'] + $inventory_lists, (isset($item->parent_id) ? $item->parent_id : null), ['class'=>'form-control']) !!}
        <span class="label label-danger">{{ $errors->first('parent_id', ':message') }}</span>
    </div>
</div>
 -->

<table id="data-grid" class="results table table-hover"  data-grid="main">
          <thead>
            <tr>
                <th class="sortable" >Kit Name</th>
                <th class="sortable" >Kit Stocks Name</th>
                <th class="sortable" >Serial no</th>
                <th class="sortable" >Quantity</th> 
            </tr>
          </thead>
          <tbody>
             @foreach($inventory_lists as $key => $value)
            <tr>
                <th>{{ ucfirst($kitname[0]) }}</th>
                <th>{!! Form::checkbox('inventory_id[]', $value->id, (isset($results) && in_array($value->id,$results))?'checked':false) !!}
                    {!! $value->name !!}
                </th>
                <th>
                    @if($value->is_seriealno==1)
                        {!! Form::text('serial_no[]', (isset($value) ? $value->serial_no : null), ['class'=>'col-md-8', 'placeholder'=>'Serial no']) !!}
                    @else
                        {!! Form::hidden('serial_no[]', 0) !!}
                        N/A
                    @endif
                </th>
                <th>   
                    @if($value->is_seriealno==0)
                    {!! Form::text('quantity[]', (isset($results) &&  in_array($value->id,$results))?$quantity[$key]:null, ['class'=>'col-md-5', 'placeholder'=>'quantity']) !!}
                    @else
                    {!! Form::hidden('quantity[]', 1) !!}
                    <span style="line-height: 36px;">1</span>
                    @endif
                    
                </th> 
            </tr>
            @endforeach
          </tbody>
        </table>

{!! Form::hidden('kit_id', $kit_id) !!}

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
        <input type="button" class="btn btn-primary" value="Back" onclick="return window.history.back();">
    </div>
</div>
 