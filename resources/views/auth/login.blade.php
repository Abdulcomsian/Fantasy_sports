@extends('layouts.new-default')
@section('title', 'Login')
<style>
    .heading h1{
        font-family: "olympus" !important;
        font-size: 85px;
        font-weight: 900;
        line-height: 104px !important;     
    }
    .fantasySeason{
        font-family: "olympus" !important;
    }
</style>
@section('content')
<div class="create_league">
     <div class="overlay"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="heading">
                    <h1>The <br> Offseason <br> GM</h1>
                </div>
                <p class="fantasySeason">WHERE THE FANTASY SEASON <span>NEVER END</span></p>
            </div>
            <div class="col-lg-6">
            <form name="signin" action="{{ route('login') }}" method="POST" class="loginForm">
            @csrf
            <input type="hidden" name="key" value="@if(isset($key)){{$key}}@else{{''}}@endif" />
                    <div class="form-group">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="create_league_table">
                        <div class="save lg-btn">
                            <button>Sign In</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 offset-md-3 forget-pasw">
                    <a href="/forgot-password" style="color:#fff;margin-right:20px;">Forgot Password?</a>
                    @php if(!isset($key)){$key='';} @endphp
                    <a href="{{url('register?key='.$key.'')}}" style="color:#fff;">Sign Up</a>
                </div>
            </div>
            <!-- <div class="row">
               
            </div> -->
        </form>
            </div>
        </div>
       
    </div>
</div>
@stop