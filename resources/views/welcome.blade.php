@extends('layouts.default')
@section('title', 'Welcome')
@section('content')
<div>
    <div class="container-fluid">
        <div class="row">
            @if(!Auth::check())
            <div class="col-md-3 offset-md-1">
                <div class="btn_top">
                    <a href="{{ url('login') }}">Sign in</a>
                </div>
            </div>
            @endif

             <div class="col-md-6 {{ (Auth::check()) ? 'offset-md-1' : '' }}">
                <div class="btn_top">
                    <a class="big_btn" href="{{ url('league/create')}}">Create league draftboard</a>
                </div>
            </div>
            @if(Auth::check())
            <div class="col-md-2">
                <div class="btn_top">
                    <a href="{{ url('/home') }}">Dashboard</a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="banner_heading" style="margin-bottom:30px">
        <h1>The </br>  off-season </br>   gm</h1>
        <h3>Where the fantasy season never ends</h3>
    </div>
</div>
@stop