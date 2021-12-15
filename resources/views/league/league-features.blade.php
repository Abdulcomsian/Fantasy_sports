@extends('layouts.new-default')
@section('title', 'Settings')
@section('content')
<style>
    .mainContent{
        text-align: center;
    }
    .mainContent h3{
        color: #fff !important;
        font-size: 50px;
        font-weight: 700;
        margin-bottom: 60px;
    }
    .mainContent .listDiv ul{
        padding: 0px;
        list-style: none;
    }
    .mainContent .listDiv li{
        color: #fff;
        font-size: 18px;
        text-align: left;
        display: flex;
        justify-content: space-between;
    }
    .redText{
        color: red !important;
        font-weight: 700;
    }
    .mainContent .listDiv li p:nth-child(1){
        margin-right: 30px;
    }
    .mainContent .listDiv li p img{
        width: 100px;
        
    }
    .mainContent .listDiv li:nth-child(2) p img,
    .mainContent .listDiv li:nth-child(4) p img{
        width: 55px;
    }
    .imgDiv{
        position: relative;
    }
    .imgDiv img:nth-child(1){
        z-index: -1;
        position: absolute;
        width: 100%;
        max-width: 70%;
        box-shadow: -2px -2px 19px -1px rgba(240,220,220,0.7);
-webkit-box-shadow: -2px -2px 19px -1px rgba(240,220,220,0.7);
-moz-box-shadow: -2px -2px 19px -1px rgba(240,220,220,0.7);
    }
    .imgDiv img:nth-child(2){
        z-index:999999;
        position: relative;
        top: 150px;
        box-shadow: -2px -2px 19px -1px rgba(240,220,220,0.7);
-webkit-box-shadow: -2px -2px 19px -1px rgba(240,220,220,0.7);
-moz-box-shadow: -2px -2px 19px -1px rgba(240,220,220,0.7);
    }
    .loginView{
    background-image: url("../images/loginBg.png") !important;
    height: 100vh !important;
}
</style>

<div class="create_league_table assign_order the_lottery squads_board draft_boards setting create_league">
	<div class="container-fluid">
		<div class="alert alert-warning alert-dismissible hide" role="alert">
			<span class="message"></span>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
        <div class="mainContent">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="imgDiv">
                        <img src="{{asset('images/editMode.png')}}" alt="" class="img-fluid">
                            <img src="{{asset('images/draftMode.png')}}" alt="" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h3>Features</h3>
                        <div class="listDiv">
                            <ul>
                                <li><p><img src="{{asset('images/right-angle.png')}}" alt="" class="img-fluid"></p> <p>Enables commissioners who are in highly active year round leagues, to immerse themselves in an fully interactive & customizable draft board</p></li>
                                <li><p><img src="{{asset('images/right-angle.png')}}" alt="" class="img-fluid"></p> <p>Version control that allows you to always see the most up to date draft board</p></li>
                                <li><p><img src="{{asset('images/right-angle.png')}}" alt="" class="img-fluid"></p> <p>Fully integrated platform that allows commissioners to invite other owners, kickoff and manage off season transactions and activities up to draft day!</p></li>
                                <li><p><img src="{{asset('images/right-angle.png')}}" alt="" class="img-fluid"></p> <p>Live Draft Mode & Edit Mode that allows the commish to go live or get back in the lab!</p></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		
</div>
</div>

@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/league/settings.js') }}"></script>

@endsection