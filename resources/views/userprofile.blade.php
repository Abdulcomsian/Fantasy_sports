@extends('layouts.default')
@section('title', 'Edit Profile')
@section('content')
<style type="text/css">
    label{
        color:white;
    }
</style>
<div class="create_league_table assign_order the_lottery draft_boards draft_room">
    <div class="container">
        <div class="row">
            @if(session('message'))
            <div class="alert alert-success" id="success-alert">
               <button type="button" class="close" data-dismiss="alert">x</button>
               <strong>{{session('message')}}</strong>
            </div>
            
            @endif
            <!-- <div class="col-md-3">
            </div> -->
            <div class="col-md-12">
                <div class="side_detail">
                    <h4>Edit Profile</h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible hide" role="alert">
                    <span class="message"></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <br>
                <form method="post" action="{{route('updateprofile')}}">
                    @csrf
                    <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" class="form-control" id="name" name="name"  placeholder="Enter Name" value="{{Auth::user()->name}}" required="required">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" value="{{Auth::user()->email}}" required="required">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password" value="">
                  </div>
                  <button style="background-image: linear-gradient(to right, #000, #353535);font: 700 20px;color: #fff;border: 1px solid #fff;padding: 13px 33px;" type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

@endsection