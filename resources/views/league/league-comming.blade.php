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
        margin-bottom: 60px;
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
    }

    .redText {
        color: red !important;
        font-weight: 700;
    }

    .mainContent .listDiv li p:nth-child(1) {
        margin-right: 30px;
    }

    .mainContent .listDiv li p img {
        width: 55px;

    }

    .mainContent .listDiv li:nth-child(3) p img,
    .mainContent .listDiv li:nth-child(4) p img {
        width: 80px;
    }

    .mainContent .listDiv li:nth-child(2) p img {
        width: 55px;
    }

    .imgDiv {
        position: relative;
    }

    .imgDiv img {
        box-shadow: -2px -2px 19px -1px rgba(240, 220, 220, 0.7);
        -webkit-box-shadow: -2px -2px 19px -1px rgba(240, 220, 220, 0.7);
        -moz-box-shadow: -2px -2px 19px -1px rgba(240, 220, 220, 0.7);
    }

    .loginView {
        background-image: url("../images/loginBg.png") !important;
        height: 100vh !important;
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
                        <div class="listDiv">
                            <ul>
                                <li>
                                    <p><img src="{{asset('images/right-angle.png')}}" alt="" class="img-fluid"></p>
                                    <p>Multiple draft boards, for multiple leagues, no matter the platform</p>
                                </li>
                                <li>
                                    <p><img src="{{asset('images/right-angle.png')}}" alt="" class="img-fluid"></p>
                                    <p>Designate Keepers and keeper Values</p>
                                </li>
                                <li>
                                    <p><img src="{{asset('images/right-angle.png')}}" alt="" class="img-fluid"></p>
                                    <p>Fills off season gaps that active leagues require during the year, from Draft Lottery Simulation to Live Draft</p>
                                </li>
                                <li>
                                    <p><img src="{{asset('images/right-angle.png')}}" alt="" class="img-fluid"></p>
                                    <p>Specifically designed for active keeper leagues where transactions and management is required by the commish</p>
                                </li>
                                <li>
                                    <p><img src="{{asset('images/right-angle.png')}}" alt="" class="img-fluid"></p>
                                    <p>Create a one stop shop for off season business</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="imgDiv">
                            <img src="{{asset('images/leagueDraft.png')}}" alt="" class="img-fluid">
                        </div>
                    </div>

                </div>
                <div class="row" style="margin-top: 0px">
                    <div class="col-lg-4">
                        <div class="listDiv commingSoonList">
                            <ul>
                                <li>Draft Lottery Pick Simulation <span class="redText"> Coming Soon!</span></li>
                                <li>Mock Drafting <span class="redText"> Coming Soon!</span></li>
                                <li>GM Dashboard <span class="redText"> Coming Soon!</span></li>
                                <li>The Offseason GM App <span class="redText"> Coming Soon!</span></li>
                            </ul>
                        </div>

                    </div>
                    <div class="col-lg-8">
                        <div class="imgDiv">
                            <img src="{{asset('images/commingEdit.png')}}" alt="" class="img-fluid">
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