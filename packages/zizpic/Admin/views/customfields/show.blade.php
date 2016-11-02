@extends('layouts.master')
 
@section('content')
<div class="content-wrapper">
    @include('packages::location.grid.sectionhead')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
              <div class="box-body">
<div class="form-group col-sm-12">
    <label class="col-sm-2 control-label">Name</label>
    <div class="col-md-4">
    {{  (isset($location) ? $location->name : null) }}
    <span class="label label-danger"></span>
    </div>
</div>

<div class="form-group col-sm-12">
    <label class="col-sm-2 control-label">Category</label>
    <div class="col-md-4">
        {{ (isset($location) ? $location_cat->name : null) }}
        <span class="label label-danger"></span>
    </div>
</div>

                </div>
            </div>
        </div>
    </section>
</div>
@stop


    