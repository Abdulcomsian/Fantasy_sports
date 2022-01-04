@extends('layouts.new-default')
@section('title', 'Settings')
@section('content')
<style>
    .mainContent {
        text-align: center;
    }

    .mainContent h3 {
        color: #fff !important;
        font-size: 50px;
        font-weight: 700;
        font-family: "impact-Font" !important;
    }

    .mainContent p {
        color: #fff;
        font-size: 18px;
        font-family: "impact-Font" !important;
        font-weight: 300 !important;
    }

    .redText {
        color: red !important;
        font-weight: 700;
    }

    .loginView {
        background-image: url("../images/loginBg.png") !important;
        height: 100vh !important;
    }

    .imgDiv {
        position: relative;
    }

    .imgDiv img:nth-child(1) {
        z-index: -1;
        position: absolute;
        width: 100%;
        max-width: 70%;
        box-shadow: -2px -2px 19px -1px rgba(240, 220, 220, 0.7);
        -webkit-box-shadow: -2px -2px 19px -1px rgba(240, 220, 220, 0.7);
        -moz-box-shadow: -2px -2px 19px -1px rgba(240, 220, 220, 0.7);
    }

    .imgDiv img:nth-child(2) {
        z-index: 999999;
        position: relative;
        top: 150px;
        box-shadow: -2px -2px 19px -1px rgba(240, 220, 220, 0.7);
        -webkit-box-shadow: -2px -2px 19px -1px rgba(240, 220, 220, 0.7);
        -moz-box-shadow: -2px -2px 19px -1px rgba(240, 220, 220, 0.7);
    }

    .mainContent .listDiv ul {
        padding: 0px;
        list-style: none;
    }

    .mainContent .listDiv li {
        color: #fff;
        font-size: 18px;
        text-align: left;
        display: flex;
        justify-content: space-between;
        font-family: "impact-Font" !important;
        font-weight: 300 !important;
    }

    .mainContent .listDiv3 li {
        color: #fff;
        font-size: 18px;
        text-align: left;
        display: flex;
        font-family: "impact-Font" !important;
        font-weight: 300 !important;
    }

    .redText {
        color: red !important;
        font-weight: 700;
    }

    .mainContent .listDiv li p:nth-child(1) {
        margin-right: 30px;
    }

    .mainContent .listDiv li:nth-child(2) p img,
    .mainContent .listDiv li:nth-child(4) p img {
        width: 55px;
    }

    .mainContent .listDiv li p img {
        width: 100px;

    }

    .mainContent .listDiv3 li p img {
        width: 55px;

    }

    .commingSoonList {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .commingSoonList ul li {
        text-align: center !important;
        justify-content: center;
    }

    .mainContent .listDiv3 li p:nth-child(1) {
        margin-right: 30px;
    }

    .mainContent .listDiv3 li p img {
        width: 55px;

    }

    .mainContent .listDiv3 li:nth-child(3) p img,
    .mainContent .listDiv3 li:nth-child(4) p img {
        width: 80px;
    }

    .mainContent .listDiv3 li:nth-child(2) p img {
        width: 55px;
    }

    .imgDiv3 {
        position: relative;
    }

    .imgDiv3 img {
        box-shadow: -2px -2px 19px -1px rgba(240, 220, 220, 0.7);
        -webkit-box-shadow: -2px -2px 19px -1px rgba(240, 220, 220, 0.7);
        -moz-box-shadow: -2px -2px 19px -1px rgba(240, 220, 220, 0.7);
    }
    .create_league{
        position: relative;
        z-index: 999999;
		height: 100% !important;
    }
    .loginView .create_league .heading h1{
        text-align: center;
    }
    .topDiv{
        position: relative;
    }
    .topDiv img{
        width: 220px;
        position: absolute;
        right: 15%;
        top: -18px;
    }
    .topDiv .arrowText:after{
        content: "";
        background-image: url("../images/upArrow.png");
        width: 228px;
        position: absolute;
        bottom: 0px;
        background-repeat: no-repeat;
        background-position: center;
        z-index: 99999999999;
        display: inline-block;
        height: 50px;
        background-size: auto;
    }
    .topDiv .redText{
        position: relative;
    }
</style>

<div class="create_league_table assign_order the_lottery squads_board draft_boards setting create_league">
<div class="overlay"></div>
    <div class="container-fluid">
        <div class="alert alert-warning alert-dismissible hide" role="alert">
            <span class="message"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="mainContent">

            <div class="row">
                <div class="col-lg-12">
                    <div class="topDiv">
                        <img src="{{asset('images/cloud.png')}}" alt="" class="img-fluid">
                        <h3>LEAGUE PREVIEW</h3>
                        <p style="display:block;">Welcome to the OFFSEASON GM!</p>
                        <p class="redText arrowText" style="display:block;">A PLACE WHERE THE FANTASY SEASON NEVER ENDS!</p>
                        <p>This is the ultimate site designed specifically for commissioners & owners that play in highly active fantasy leagues! From the start of the offseason, up until draft day, we got you covered! THE OFFSEASON GM helps keeper leagues organize & enhance the off season action for both commissioners, and team owners by creating an interactive draft board and league experience!</p>
                        <p>Fantasy commissioners are always on the clock, and we are here to unlock and fill in the gap of what other major platforms are missing for better off season preparation!</p>
                        <p>Year after year -As the commish, and member of serious and competitive leagues, I have begrudgingly managed and tracked multiple spreadsheets of traded draft picks and keepers, through multiple sports, group chats, leagues & platforms. Keeper leagues that have unique and customized keeper rules and involvement. This has become a manually intensive process, riddled with human error that can cause serious live offline draft day issues! This platform will provide higher league participation, functionality, and organization by making you the best commish and GM in all of fantasy!</p>
                        <p class="redText">NOW GO WIN THE SEASON -- IN THE OFF SEASON!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="imgDiv">
                        <img src="{{asset('images/1.png')}}" alt="" class="img-fluid">
                        <img src="{{asset('images/2.png')}}" alt="" class="img-fluid">
                    </div>
                </div>
                <div class="col-lg-6">
                    <h3>Features</h3>
                    <div class="listDiv3">
                        <ul>
                            <li>
                                <p><img src="{{asset('images/right-angle.png')}}" style="width:80px;" alt="" class="img-fluid"></p>
                                <p>Enables commissioners who are in highly active year round leagues, to immerse themselves in an fully interactive & customizable draft board</p>
                            </li>
                            <li>
                                <p><img src="{{asset('images/right-angle.png')}}" style="width:55px;" alt="" class="img-fluid"></p>
                                <p>Version control that allows you to always see the most up to date draft board</p>
                            </li>
                            <li>
                                <p><img src="{{asset('images/right-angle.png')}}" style="width:95px;" alt="" class="img-fluid"></p>
                                <p>Fully integrated platform that allows commissioners to invite other owners, kickoff and manage off season transactions and activities up to draft day!</p>
                            </li>
                            <li>
                                <p><img src="{{asset('images/right-angle.png')}}" style="width:55px;" alt="" class="img-fluid"></p>
                                <p>Live Draft Mode & Edit Mode that allows the commish to go live or get back in the lab!</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="row" style="margin-top: 300px">
                <div class="col-lg-6">
                    <div class="listDiv3">
                        <ul>
                            <li>
                                <p><img src="{{asset('images/right-angle.png')}}" alt="" style="width:55px;" class="img-fluid"></p>
                                <p>Multiple draft boards, for multiple leagues, no matter the platform</p>
                            </li>
                            <li>
                                <p><img src="{{asset('images/right-angle.png')}}" alt="" style="width:55px;" class="img-fluid"></p>
                                <p>Designate Keepers and keeper Values</p>
                            </li>
                            <li>
                                <p><img src="{{asset('images/right-angle.png')}}" alt="" style="width:55px;" class="img-fluid"></p>
                                <p>Fills off season gaps that active leagues require during the year, from Draft Lottery Simulation to Live Draft</p>
                            </li>
                            <li>
                                <p><img src="{{asset('images/right-angle.png')}}" alt="" style="width:55px;" class="img-fluid"></p>
                                <p>Specifically designed for active keeper leagues where transactions and management is required by the commish</p>
                            </li>
                            <li>
                                <p><img src="{{asset('images/right-angle.png')}}" alt="" style="width:55px;" class="img-fluid"></p>
                                <p>Create a one stop shop for off season business</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="imgDiv3">
                        <img src="{{asset('images/3.png')}}" alt="" class="img-fluid">
                    </div>
                </div>

            </div>
            <div class="row" style="margin-top: 30px">
                <div class="col-lg-4">
                    <div class="listDiv3 commingSoonList">
                        <ul>
                            <li>Draft Lottery Pick Simulation <span class="redText"> Coming Soon!</span></li>
                            <li>Mock Drafting <span class="redText"> Coming Soon!</span></li>
                            <li>GM Dashboard <span class="redText"> Coming Soon!</span></li>
                            <li>The Offseason GM App <span class="redText"> Coming Soon!</span></li>
                        </ul>
                    </div>

                </div>
                <div class="col-lg-8">
                    <div class="imgDiv3">
                        <img src="{{asset('images/4.png')}}" alt="" class="img-fluid">
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