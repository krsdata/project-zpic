<div class="form-group">
    <label class="col-sm-2 control-label">Name</label>
    <div class="col-md-4">
    {!! Form::text('name', (isset($supplier) ? $supplier->name : null), ['class'=>'form-control', 'placeholder'=>'Name']) !!}
    <span class="label label-danger">{{ $errors->first('name', ':message') }}</span>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Address</label>
    <div class="col-md-4">
        {!! Form::textarea('address', (isset($supplier) ? $supplier->address : null), ['class'=>'form-control', 'placeholder'=>'Address', 'rows'=>'5']) !!}
        <span class="label label-danger">{{ $errors->first('address', ':message') }}</span>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Postal Code</label>
    <div class="col-md-4">
        {!! Form::text('postal_code', (isset($supplier) ? $supplier->postal_code : null), ['class'=>'form-control', 'placeholder'=>'Postal Code']) !!}
        <span class="label label-danger">{{ $errors->first('postal_code', ':message') }}</span>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Zip Code</label>
    <div class="col-md-4">
        {!! Form::text('zip_code', (isset($supplier) ? $supplier->zip_code : null), ['class'=>'form-control', 'placeholder'=>'Zip Code']) !!}
        <span class="label label-danger">{{ $errors->first('zip_code', ':message') }}</span>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Region</label>
    <div class="col-md-4">
        {!! Form::text('region', (isset($supplier) ? $supplier->region : null), ['class'=>'form-control', 'placeholder'=>'Region']) !!}
        <span class="label label-danger">{{ $errors->first('region', ':message') }}</span>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">City</label>
    <div class="col-md-4">
        {!! Form::text('city', (isset($supplier) ? $supplier->city : null), ['class'=>'form-control', 'placeholder'=>'City']) !!}
        <span class="label label-danger">{{ $errors->first('city', ':message') }}</span>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Country</label>
    <div class="col-md-4">
        {!! Form::text('country', (isset($supplier) ? $supplier->country : null), ['class'=>'form-control', 'placeholder'=>'Country']) !!}
        <span class="label label-danger">{{ $errors->first('country', ':message') }}</span>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Contact Title</label>
    <div class="col-md-4">
        {!! Form::text('contact_title', (isset($supplier) ? $supplier->contact_title : null), ['class'=>'form-control', 'placeholder'=>'Contact Title']) !!}
        <span class="label label-danger">{{ $errors->first('contact_title', ':message') }}</span>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Contact Name</label>
    <div class="col-md-4">
        {!! Form::text('contact_name', (isset($supplier) ? $supplier->contact_name : null), ['class'=>'form-control', 'placeholder'=>'Contact Name']) !!}
        <span class="label label-danger">{{ $errors->first('contact_name', ':message') }}</span>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Contact Email</label>
    <div class="col-md-4">
        {!! Form::text('contact_email', (isset($supplier) ? $supplier->contact_email : null), ['class'=>'form-control', 'placeholder'=>'Contact Email']) !!}
        <span class="label label-danger">{{ $errors->first('contact_email', ':message') }}</span>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Contact Phone</label>
    <div class="col-md-4">
        {!! Form::text('contact_phone', (isset($supplier) ? $supplier->contact_phone : null), ['class'=>'form-control', 'placeholder'=>'Contact Phone']) !!}
        <span class="label label-danger">{{ $errors->first('contact_phone', ':message') }}</span>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Contact Fax</label>
    <div class="col-md-4">
        {!! Form::text('contact_fax', (isset($supplier) ? $supplier->fax : null), ['class'=>'form-control', 'placeholder'=>'Contact Fax']) !!}
                <span class="label label-danger">{{ $errors->first('fax', ':message') }}</span>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($btn_title, ['class'=>'btn btn-primary']) !!}
        <input type="button" class="btn btn-primary" value="Back" onclick="return window.history.back();">
    </div>
</div>