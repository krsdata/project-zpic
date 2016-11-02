@if(!isset($transactionState->created_at))
<div class="form-group{{ $errors->first('state', ' has-error') }}">
    {!! Form::label('state', 'State:',['class'=>'col-sm-2 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('state',null, ['class'=>'form-control', 'placeholder'=>'State','readonly'=>'']) !!}
        <span class="label label-danger">{{ $errors->first('state', ':message') }}</span>
    </div>
</div>
@endif

<div class="form-group{{ $errors->first('description', ' has-error') }}">
    {!! Form::label('description', 'Description:',['class'=>'col-sm-2 control-label']) !!}
    <div class="col-md-4">
        {!! Form::textarea('description', null, ['class'=>'form-control', 'placeholder'=>'Description']) !!}
        <span class="label label-danger">{{ $errors->first('description', ':message') }}</span>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
    </div>
</div>
