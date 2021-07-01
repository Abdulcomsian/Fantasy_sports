@extends('layouts.default')
@section('title', 'City Chart')
@section('content')
<div class="season_fall create_league_table assign_order the_lottery traders">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="side_detail">
                    <h4>Trades</h4>
                    <div class="pedding">
                        <h3>Pending Offers (1)</h3>
                        <h5>My Picks</h5>
                        <ul class="list-unstyled">
                            <li>1.1</li>
                            <li>1.12</li>
                            <li>2.7</li>
                            <li>2.12</li>
                            <li>3.1</li>
                            <li>5</li>
                            <li>6.2</li>
                            <li>7</li>
                            <li>8</li>
                            <li>9</li>
                            <li>10</li>
                            <li>11</li>
                            <li>12.4</li>
                            <li>12.7</li>
                            <li>13</li>
                            <li>15</li>
                            <li>17</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-8">
                <div class="pending_trade">
                    <h3>Pending Trade Proposal</h3>
                </div>

                <div class="slide">
             
                    <div class="row vide_slide">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="slide_desc">
                                        <h4>Deezy Trades</h4>
                                        <h2>DJ Chark (WR) Pick 10.6</h2>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="frsh">
                                        <img src="{{ asset('images/refresh.png') }}">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="slide_desc">
                                        <h4>Deezy Trades</h4>
                                        <h2>DJ Chark (WR) Pick 10.6</h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="slide_desc">
                                        <h4>Deezy Trades</h4>
                                        <h2>DJ Chark (WR) Pick 10.6</h2>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="frsh">
                                        <img src="{{ asset('images/refresh.png') }}">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="slide_desc">
                                        <h4>Deezy Trades</h4>
                                        <h2>DJ Chark (WR) Pick 10.6</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="btn_list">
                        <ul class="list-unstyled list-inline">
                            <li class="list-inline-item">
                                <button>Cancel Offer</button>
                            </li>
                            <li class="list-inline-item">
                                <button>Accept</button>
                            </li>
                            <li class="list-inline-item">
                                <button>Reject</button>
                            </li>
                            <li class="list-inline-item">
                                <button>Counter</button>
                            </li>
                        </ul>
                    </div>

                    <div class="propose_trade">
                        <h3>Propose Trade</h3>
                        <form>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="list">
                                        <h4>Deezy </h4>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Player Search...">
                                            <button>Add</button>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Draft Pick Search">
                                            <button>Add</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2"></div>
                                <div class="col-md-5">
                           
                                    <div class="list">
                                        <select class="form-control">
                                            @foreach($league->teams as $team)
                                            <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                            @endforeach
                                        </select>
                     
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Player Search...">
                                            <button>Add</button>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Draft Pick Search">
                                            <button>Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="submit_btn">
                                <button>Submit Offer!</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('plugins/slick/js/slick.min.js') }}"></script>
<script type="text/javascript">
    $('.vide_slide').slick({
        dots: false,
        arrows: true,
        infinite: true,
        slidesToShow: 1,
        autoplay: false,
        slidesToScroll: 1,

        responsive: [{
            breakpoint: 992,
            settings: {
                dots: false,
                infinite: true,
                 slidesToShow: 1,
                slidesToScroll: 1
            }
        }, {
            breakpoint: 768,
            settings: {
                dots: false,
                arrows: false,
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }, {
            breakpoint: 480,
            settings: {
                dots: false,
                arrows: false,
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }]
    });
</script>
@endsection