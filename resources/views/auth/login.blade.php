@extends('layouts.default')
@section('title', 'Login')
@section('content')
<div class="season_fall create_league">
    <div class="container">
            <div class="heading">
            <h1>Login</h1>
        </div>
    </div>
  
    <div class="container-fluid">
        <form name="signin" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="form-group">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="form-group">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="create_league_table">
                        <div class="save lg-btn">
                            <button>Login</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 offset-md-3 forget-pasw">
                     <a href="/forgot-password"><button>Forgot Password?</button></a>
                     <a href=""><button>Don't have an account yet?<br> Sign Up</button></a>
                </div>
            </div>
            <!-- <div class="row">
               
            </div> -->
        </form>
    </div>
</div>
@stop