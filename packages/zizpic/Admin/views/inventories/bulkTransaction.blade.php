
<div class="form-group{{ $errors->first('state_lists', ' has-error') }}">
    <label class="col-sm-2 control-label">State</label>

    <div class="col-md-4">
        {!! Form::select('state', [null=>'Select State']+$state_lists , null, ['class'=>'form-control','id'=>'state']) !!}
        <span class="label label-danger state"></span>
    </div>
</div>

<div class="form-group{{ $errors->first('state_lists', ' has-error') }}">
    <label class="col-sm-2 control-label">Name</label>

    <div class="col-md-4">
        {!! Form::textarea('name',null, ['placeholder'=>'Reason','size' => '30x3','id'=>'name']) !!}
        <span class="label label-danger name">{{ $errors->first('name', ':message') }}</span>
    </div>
</div>
<div class="form-group{{ $errors->first('state_lists', ' has-error') }}">
    <label class="col-sm-2 control-label">Quantity</label>

    <div class="col-md-4">
        {!! Form::number('quantity',null, ['placeholder'=>'quantity','id'=>'quantity','min'=>1]) !!}
        <span class="label label-danger quantity">{{ $errors->first('quantity', ':message') }}</span>
    </div>
</div>