@extends('auth.layout')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 col-sm-offset-1 col-md-offset-2 col-lg-offset-3">
        @if(Session::has('global'))
        <div class="alert alert-warning">
            {{ Session::get('global') }}
        </div>
        @endif
<!-- -->
<form method="POST" action="/auth/login" class="form-signin well col-xs-10 col-sm-8 col-md-6 col-lg-6  col-sm-offset-2 col-md-offset-3 col-lg-offset-3">
    {!! csrf_field() !!}
    <div class="form-group">
        Email
        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
    </div>
    <div class="form-group">
        Password
        <input type="password" name="password" class="form-control" id="password">
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Login</button>
    </div>
</form>
<!-- -->
        <div class="clearfix"></div>
    </div>
</div>
@stop