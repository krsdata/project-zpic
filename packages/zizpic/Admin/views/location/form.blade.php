<div class="form-group{{ $errors->first('name', ' has-error') }}">
    <label class="col-sm-2 control-label">Name</label>

    <div class="col-md-4">
        {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'USA Main Warehouse']) !!}

        <span class="label label-danger">{{ $errors->first('name', ':message') }}</span>
    </div>
</div>

<div class="form-group{{ $errors->first('description', ' has-error') }}">
    {!! Form::label('description', 'Description:',['class'=>'col-sm-2 control-label']) !!}
    <div class="col-md-4">
        {!! Form::textarea('description', null, ['class'=>'form-control', 'placeholder'=>'Description']) !!}
        <span class="label label-danger">{{ $errors->first('description', ':message') }}</span>
    </div>
</div>

@if(count($data))
@foreach ( $data as $key => $customFieldData )
@if ( $key === 'select_box' )
@foreach ( $customFieldData as $key => $name )
<div class="form-group">
    {!! Form::label($name, str_replace('_'," ",$name),['class'=>'col-sm-2 control-label']) !!}
    <div class="col-md-4">
        <select class="form-control" id="choices" name="{{str_replace(" ",'_',$name)}}" >
            @foreach ( $data[ 'select' ][ $key ] as $key => $choice )
            <option value="{{$choice}}" @if(isset($custom_record[str_replace(' ',"_",$name)]) && $custom_record[str_replace(' ',"_",$name)] ==$choice) {{ 'selected' }} @endif  >{{ $choice }}</option>
            @endforeach
        </select>
    </div>
</div>
@endforeach
@endif
@if ( $key === 'radio_box' )
@foreach ( $customFieldData as $key => $name )
<div class="form-group">
    {!! Form::label($name, $name,['class'=>'col-sm-2 control-label']) !!}
    <div class="col-md-4">
        @foreach ( $data[ 'radio' ][ $key ] as $key => $choice )
        <p>{{$choice}}
            {!! Form::radio($name, $choice) !!}
        </p>
        @endforeach
    </div>
</div>
@endforeach
@endif

<!--@if ( $key == 'check_box' )
@foreach ( $customFieldData as $key => $name )
<div class="form-group">
    {!! Form::label($name, $name) !!}
    @foreach ( $data[ 'checkbox' ][ $key ] as $key => $choice )
    <p>{{$choice}}
        {!! Form::checkbox($name, $choice,(isset($custom_record[$name]) && $custom_record[$name]==$name)?'checked':null ) !!}
    </p>
    @endforeach
</div>
@endforeach
@endif-->


@if ( $key === 'text_box' )
@foreach ( $customFieldData as $key => $name )
<div class="form-group">
    {!! Form::label($name, $name,['class'=>'col-sm-2 control-label']) !!}
    <div class="col-md-4">
        @foreach ( $data[ 'text' ][ $key ] as $key => $choice )
        {!! Form::text($name, (isset($custom_record[str_replace(' ',"_",$name)]))?$custom_record[str_replace(' ',"_",$name)]:'') !!}
        @endforeach
    </div>
</div>
@endforeach
@endif

@endforeach
@endif



<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit('Save', array('class'=>'btn btn-primary')) !!}
        <input type="button" class="btn btn-primary" value="Back" onclick="return window.history.back();">
    </div>
</div>
