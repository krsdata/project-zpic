@extends('layouts.front-end')
    @section('content')
        <div class="container">
            <form class="form-horizontal" role="form">
                <fieldset>
                    <div class="row">
                        <div>
                            <img src="{{  url('assets/images/logo.jpg') }}" />
                        </div>
                        <h2>zizpic order</h2>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <h5 class="with-bg">
                                <span class="required">Zizpic 1</span>
                            </h5>
                            <input type="text" readonly="readonly" class="form-control upload-image" placeholder="UPLOAD IMAGE" />
                            <input type="file" class="hide" />
                            <h5>
                                <span>3 Words for zizpic 1</span>
                            </h5>
                            <input type="text" class="form-control" placeholder="Tune" />
                            <input type="text" class="form-control" placeholder="Look" />
                            <input type="text" class="form-control" placeholder="Activate" />
                        </div>
                        <div class="col-md-4">
                            <h5 class="with-bg">
                                <span class="required">Zizpic 1</span>
                            </h5>
                            <input type="text" readonly="readonly" class="form-control upload-image" placeholder="UPLOAD IMAGE" />
                            <input type="file" class="hide" />
                            <h5>
                                <span>3 Words for zizpic 1</span>
                            </h5>
                            <input type="text" class="form-control" placeholder="Tune" />
                            <input type="text" class="form-control" placeholder="Look" />
                            <input type="text" class="form-control" placeholder="Activate" />
                        </div>
                        <div class="col-md-4">
                            <h5 class="with-bg">
                                <span class="required">Zizpic 1</span>
                            </h5>
                            <input type="text" readonly="readonly" class="form-control upload-image" placeholder="UPLOAD IMAGE" />
                            <input type="file" class="hide" />
                            <h5>
                                <span>3 Words for zizpic 1</span>
                            </h5>
                            <input type="text" class="form-control" placeholder="Tune" />
                            <input type="text" class="form-control" placeholder="Look" />
                            <input type="text" class="form-control" placeholder="Activate" />
                        </div>
                </fieldset>
             </form>
        </div>
    @stop        