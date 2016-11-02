@if( Config::get( 'app.parent_inventory_id' ) ==='yes')
<div class="form-group{{ $errors->first('parent_id', ' has-error') }}">
    <label class="col-sm-2 control-label">Parent Inventory</label>

    <div class="col-md-4">
        {!! Form::select('parent_id', [null=>'Please Select'] + $inventory_lists, (isset($item->parent_id) ? $item->parent_id : null), ['class'=>'form-control']) !!}
        <span class="label label-danger">{{ $errors->first('parent_id', ':message') }}</span>
    </div>
</div>
@endif
@if(count($category_lists)>1)
<div class="form-group{{ $errors->first('category_id', ' has-error') }}">
    <label class="col-sm-2 control-label">Category</label>

    <div class="col-md-4">
        {!! Form::select('category_id', [null=>'Please Select'] + $category_lists, (isset($item->category_id) ? $item->category_id : null), ['class'=>'form-control']) !!}
        <span class="label label-danger">{{ $errors->first('category_name', ':message') }}</span>
        <span class="label label-danger">{{ $errors->first('category_id', ':message') }}</span>
    </div>
</div>
@else
<div class="category_1">
    {!! Form::select('category_id', $category_lists, (isset($item->category_id) ? $item->category_id : null), ['class'=>'form-control','selected'=>'selected']) !!}
</div>
@endif
@if(count($metric_lists)>1)

<div class="form-group{{ $errors->first('metric_id', ' has-error') }}">
    <label class="col-sm-2 control-label">Metric</label>

    <div class="col-md-4">
        {!! Form::select('metric_id', [null=>'Please Select'] + $metric_lists, (isset($item->metric_id) ? $item->metric_id : null), ['class'=>'form-control']) !!}
        <span class="label label-danger">{{ $errors->first('metric_name', ':message') }}</span>
        <span class="label label-danger">{{ $errors->first('metric_id', ':message') }}</span>
    </div>
</div>
@else
<div class="metrics_1">
    {!! Form::select('metric_id', $metric_lists, (isset($item->metric_id) ? $item->metric_id : null), ['class'=>'form-control','selected'=>'selected']) !!}
</div>
@endif
<div class="form-group{{ $errors->first('name', ' has-error') }}">
    <label class="col-sm-2 control-label">Name</label>
    <div class="col-md-4">
        {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Name']) !!}
        <span class="label label-danger">{{ $errors->first('name', ':message') }}</span>
    </div>
</div>

<div class="form-group{{ $errors->first('part_number', ' has-error') }}">
    {!! Form::label('Part number', 'Part number:',['class'=>'col-sm-2 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('part_number', null, ['class'=>'form-control', 'placeholder'=>'Part number']) !!}
        <span class="label label-danger">{{ $errors->first('part_number', ':message') }}</span>
    </div>
</div>

<div class="form-group{{ $errors->first('description', ' has-error') }}">
    <label class="col-sm-2 control-label">Description</label>

    <div class="col-md-4">
        {!! Form::textarea('description', null, ['class'=>'form-control', 'placeholder'=>'Description','rows'=>5]) !!}

        <span class="label label-danger">{{ $errors->first('description', ':message') }}</span>
    </div>
</div>

<div class="form-group{{ $errors->first('is_serialno', ' has-error') }}">
    <label class="col-sm-2 control-label">Enable Serial No</label>

    <div class="col-md-4">
        {!! Form::checkbox('is_serialno', 1, null) !!}
        <span class="label label-danger">{{ $errors->first('is_serialno', ':message') }}</span>
    </div>
</div>
<div class="form-group{{ $errors->first('is_assembly', ' has-error') }}">
    <label class="col-sm-2 control-label">Is {{ ucfirst(Config::get( 'app.assembly' ) ) }}?</label>
    <div class="col-md-4">
        {!! Form::checkbox('is_assembly', 1, null,['v-model'=>'inventory.is_assembly','v-attr'=>'disabled: inventory.id']) !!}
        <span class="label label-danger">{{ $errors->first('is_assembly', ':message') }}</span>
    </div>
</div>
<div class="form-group" v-show="inventory.is_assembly">
    <label class="col-sm-2 control-label">Add Item</label>
    <div class="col-md-8">
        <div class='col-md-6'>
            {!! Form::select('part_id', $inventory_lists, null, ['id'=>'part_id','class'=>'form-control','v-model'=>'newPart.part_id | partIdValidator','v-attr'=>'disabled: inventory.id']) !!}
            <span v-show="!validation.part_id && !inventory.id" class="label label-danger">Please choose inventory item.</span>
        </div>
        <div class='col-md-6'>
            {!! Form::number('part_quantity', null, ['class'=>'form-control', 'placeholder'=>'Qt.','v-model'=>'newPart.part_quantity | partQuantityValidator | partUsedQunaity','v-attr'=>'disabled: inventory.id']) !!}
            <span v-show="!validation.part_quantity && !inventory.id" class="label label-danger">Quantity must be greater then zero.</span>
        </div>
        <div v-attr='disabled: inventory.id' v-on='click: addPart' style='margin-top:10px;' class="btn btn-primary pull-right">
            <i class="fa fa-plus"></i> Add part
        </div>
    </div>
    <div class="col-md-offset-1 col-md-8">
        <table class='table table-bordered table-hover'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tr v-repeat="part: parts">
                <td>@{{$index+1}}</td>
                <td>@{{part.part_name}}</td>
                <td>
                    <input name='assembly_part_qt[]' type='number' class='form-control' v-model='part.part_quantity' v-attr='disabled: inventory.id > 0' />
                    <input type='hidden' name='assembly_part_id[]' v-model='part.part_id' />
                </td>
                <td>
                    <button v-attr='disabled: inventory.id' v-on="click: removePart(part)">X</button>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
        <input type="button" class="btn btn-primary" value="Back" onclick="return window.history.back();">
    </div>
</div>