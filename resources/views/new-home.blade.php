@extends('layouts.default')
@section('title', 'Home')
@section('content')
<style>
    .contentDiv{
        display: flex;
        justify-content: space-between;
    }
    .contentDiv .sideBar{
        width: 30%;
    }
    .contentDiv .sideBar ul{
        list-style: none;
        padding:0px;
    }
    .contentDiv .sideBar ul li {
        color: #fff;
        padding: 10px 15px;
        font-size: 22px;
    }
    .contentDiv .viewDiv{
        width: 65%;
    }
    .contentDiv .viewDiv .leagueDiv {
        margin-bottom: 20px
    }
    .contentDiv .viewDiv .leagueDiv h4{
        color: #fff;
        text-align: center;
        font-size: 18px;
    }
    .contentDiv .viewDiv .leagueDiv img{
        height: 150px;
    }
    .contentDiv .sideBar h4{
        font: 900 20px "Lato", sans-serif;
        color: #fff;
        text-transform: uppercase;
        position: relative;
        margin-bottom: 30px;
    }
    .contentDiv .sideBar ul li.active{
        background-image: linear-gradient(to right, #000, #353535);
        font: 700 22px "Neometric";
        color: #fff;
        border: 1px solid #fff;
        text-align: center;
        margin: 0 auto 20px;
    }
</style>
<div class="create_league_table assign_order the_lottery draft_boards draft_room">
    <div class="container">
        <div class="row">
            <!-- <div class="col-md-3">
            </div> -->
            <div class="col-md-12">
                <div class="side_detail">
                    <h4>Draft Room</h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="contentDiv">
                    <div class="sideBar">
                        <h4>League Draft Board</h4>
                        <ul>
                            <li class="active">Active</li>
                            <li>Completed Draft</li>
                            <li>Create League</li>
                            <li>Renew Exsisting League</li>
                            <li>Join Exsisting League</li>
                            <li>
                                <input type="text" placeholder="Enter Join Key ....">
                            </li>
                        </ul>
                    </div>
                    <div class="viewDiv">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="leagueDiv">
                                    <h4>Gotham City 2021</h4>
                                    <img src="images/City Chart.png" alt="" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="leagueDiv">
                                    <h4>Gotham City 2021</h4>
                                    <img src="images/City Chart.png" alt="" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="leagueDiv">
                                    <h4>Gotham City 2021</h4>
                                    <img src="images/City Chart.png" alt="" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="leagueDiv">
                                    <h4>Gotham City 2021</h4>
                                    <img src="images/City Chart.png" alt="" class="img-fluid">
                                </div>
                            </div>
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
<script type="text/javascript" src="{{ asset('js/home/joiner.js') }}"></script>

@endsection