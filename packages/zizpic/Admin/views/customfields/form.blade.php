<div class="form-group{{ $errors->first('fieldable', ' has-error') }}">
    <label class="col-sm-2 control-label">Custom Field Object </label>

    <div class="col-md-4">
        {!! Form::select('fieldable', [null=>'Please Select'] + $fildable_lists, null, ['class'=>'form-control']) !!}

        <span class="label label-danger">{{ $errors->first('fieldable', ':message') }}</span>
    </div>
</div>


<div class="form-group{{ $errors->first('name', ' has-error') }}">
    <label class="col-sm-2 control-label">Name</label>

    <div class="col-md-4">
        {!! Form::text('field_name', null, ['class'=>'form-control', 'placeholder'=>'field name']) !!}

        <span class="label label-danger">{{ $errors->first('field_name', ':message') }}</span>
    </div>
</div>


<div class="form-group{{ $errors->first('field_type', ' has-error') }}">
    <label class="col-sm-2 control-label">Field Type </label>

    <div class="col-md-4">
        {!! Form::select('field_type', [null=>'Please Select','select'=>'Select box','text'=>'Textbox','radio'=>'Multiple Choice','checkbox'=>'checkbox','file'=>'File'] , null, ['class'=>'form-control','id'=>'field_type']) !!}

        <span class="label label-danger">{{ $errors->first('field_type', ':message') }}</span>
    </div>
</div>

<!--<div class="form-group {{ $errors->first('file', ' has-error') }}">
    {!! Form::label('image', 'Picture',['class'=>'col-sm-2 control-label']) !!}
    <div class="col-md-4">
        {!! Form::file('file', array('class' => 'form-control')) !!}
        <span class="label label-danger">{{ $errors->first('file', ':message') }}</span>
    </div>
</div>-->

<div class="form-group{{ $errors->first('field_placeholder', ' has-error') }}">
    <label class="col-sm-2 control-label">Placeholder</label>

    <div class="col-md-4">
        {!! Form::text('field_placeholder', null, ['class'=>'form-control', 'placeholder'=>'field placeholder']) !!}

        <span class="label label-danger">{{ $errors->first('field_placeholder', ':message') }}</span>
    </div>
</div>

<div class="form-group{{ $errors->first('field_rules', ' has-error') }}">
    {!! Form::label('Field rules', 'Required:',['class'=>'col-sm-2 control-label']) !!}
    <div class="col-md-4">
        {!! Form::checkbox('field_rules', 'required',null ) !!}
        <span class="label label-danger">{{ $errors->first('field_rules', ':message') }}</span>
    </div>
</div>

<div class="show_field" style="display: {{(isset($custom_field->field_value) && !empty($custom_field->field_value))?$custom_field->field_value:'none' }} ">
    <div class="form-group{{ $errors->first('field_value', ' has-error') }}">
        {!! Form::label('Field rules', 'Field Value:',['class'=>'col-sm-2 control-label']) !!}
        <div class="col-md-4">
            {!! Form::textarea('field_value', null, ['class'=>'form-control', 'placeholder'=>'Description','id'=>'field_textarea']) !!}
            <span class="label label-danger">{{ $errors->first('field_value', ':message') }}</span>
        </div>
    </div>
</div>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit('Save', array('class'=>'btn btn-primary')) !!}
        <input type="button" class="btn btn-primary" value="Back" onclick="return window.history.back();">
    </div>
</div>
