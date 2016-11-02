@extends('layouts.master')
 
@section('content')
<div class="content-wrapper">
    @include('packages::suppliers.grid.sectionhead')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
              <div class="box-body">
<div class="form-group col-sm-12">
    <label class="col-sm-2 control-label">Name</label>
    <div class="col-md-4">
    {{  (isset($supplier) ? $supplier->name : null) }}
    <span class="label label-danger"></span>
    </div>
</div>

<div class="form-group col-sm-12">
    <label class="col-sm-2 control-label">Address</label>
    <div class="col-md-4">
        {{ (isset($supplier) ? $supplier->address : null) }}
        <span class="label label-danger"></span>
    </div>
</div>

<div class="form-group col-sm-12">
    <label class="col-sm-2 control-label">Postal Code</label>
    <div class="col-md-4">
  {{ (isset($supplier) ? $supplier->postal_code : null) }}
        <span class="label label-danger"></span>
    </div>
</div>

<div class="form-group col-sm-12">
    <label class="col-sm-2 control-label">Zip Code</label>
    <div class="col-md-4">
     {{   (isset($supplier) ? $supplier->zip_code : null)}}
        <span class="label label-danger"></span>
    </div>
</div>

<div class="form-group col-sm-12">
    <label class="col-sm-2 control-label">Region</label>
    <div class="col-md-4">
  {{ (isset($supplier) ? $supplier->region : null) }}
        <span class="label label-danger"></span>
    </div>
</div>

<div class="form-group col-sm-12">
    <label class="col-sm-2 control-label">City</label>
    <div class="col-md-4">
      {{ (isset($supplier) ? $supplier->city : null) }}
        <span class="label label-danger"></span>
    </div>
</div>

<div class="form-group col-sm-12">
    <label class="col-sm-2 control-label">Country</label>
    <div class="col-md-4">
     {{  (isset($supplier) ? $supplier->country : null) }}
        <span class="label label-danger"></span>
    </div>
</div>

<div class="form-group col-sm-12">
    <label class="col-sm-2 control-label">Contact Title</label>
    <div class="col-md-4">
      {{  (isset($supplier) ? $supplier->contact_title : null) }}
        <span class="label label-danger"></span>
    </div>
</div>

<div class="form-group col-sm-12">
    <label class="col-sm-2 control-label">Contact Name</label>
    <div class="col-md-4">
      {{  (isset($supplier) ? $supplier->contact_name : null) }}
        <span class="label label-danger"></span>
    </div>
</div>

<div class="form-group col-sm-12">
    <label class="col-sm-2 control-label">Contact Email</label>
    <div class="col-md-4">
        {{ (isset($supplier) ? $supplier->contact_email : null) }}
        <span class="label label-danger"></span>
    </div>
</div>

<div class="form-group col-sm-12">
    <label class="col-sm-2 control-label">Contact Phone</label>
    <div class="col-md-4">
        {{  (isset($supplier) ? $supplier->contact_phone : null) }}
        <span class="label label-danger"></span>
    </div>
</div>

<div class="form-group col-sm-12">
    <label class="col-sm-2 control-label">Contact Fax</label>
    <div class="col-md-4">
        {{ (isset($supplier) ? $supplier->contact_fax : null) }}
        <span class="label label-danger"></span>
    </div>
</div>

<div class="form-group col-sm-12">
    <div class="col-sm-offset-2 col-sm-10">
         
        <input type="button" class="btn btn-primary" value="Back" onclick="return window.history.back();">
    </div>
</div>
                </div>
            </div>
        </div>
    </section>
</div>
@stop


    