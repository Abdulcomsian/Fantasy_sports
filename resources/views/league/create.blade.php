@extends('layouts.default')
@section('title', 'Home')
@section('content')
<style>
    .colorPickSelector {
        border-radius: 5px;
        width: 36px;
        height: 36px;
        margin-left: 50px;
        cursor: pointer;
        -webkit-transition: all linear .2s;
        -moz-transition: all linear .2s;
        -ms-transition: all linear .2s;
        -o-transition: all linear .2s;
        transition: all linear .2s;
    }

    .colorPickSelector:hover {
        transform: scale(1.1);
    }

    .colorPicker {
        width: 220px;
        display: flex;
        padding-left: 20px;
        padding-right: 10px;
    }

    .PickSelector {
        width: 32px;
        height: 32px;
        margin: 15px;
    }

    input[type=color] {
        display: none;
    }

    .incrementNumber {
        margin: 5px;
    }
</style>
<div class="create_league">
    <div class="container">
        <div class="heading">
            <h1>create League</h1>
        </div>
    </div>

    <div class="container-fluid">
        <div class="alert alert-warning alert-dismissible hide" role="alert">
            <span class="message"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-success alert-dismissible hide" role="alert">
            <span class="message"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="createLeague">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group text-center">
                        <label>League Name</label>
                    </div>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <!-- <label>League Name</label> -->
                        <input type="text" name="name" style="width:65%" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="select_view">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="form-group select_draft">
                            <h4 style="text-align:center;width:100%;">Draft Type</h4>
                            <ul class="list-unstyled list-inline">
                                <li class="list-inline-item">
                                    <input type="radio" class="form-control" value="snake" name="draft_type">
                                    <label>Snake</label>
                                </li>
                                <li class="list-inline-item">
                                    <input type="radio" class="form-control" value="linear" name="draft_type">
                                    <label>Linear</label>
                                </li>
                            </ul>
                        </div>

                        <!-- <div class="form-group select_draft league_size">
                            <h4>League Size</h4>
                            <ul class="list-unstyled list-inline">
                                @foreach(Config::get('teams') as $size)
                                    <li class="list-inline-item">
                                        <input type="radio" class="form-control" name="league_size" value="{{$size}}">
                                        <label>{{$size}}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div> -->
                        <div class="select_draft draft_round">
                            <h4 style="width:100%;text-align:center;">League Size</h4>
                            <ul class="list-unstyled list-inline">
                                <li class="list-inline-item">
                                    <!-- <label>17</label> -->
                                    <div class="form-group">
                                        <select class="lg-size" name="league_size">
                                            @foreach(Config::get('teams') as $size)
                                            <option class="text-dark" value="{{$size}}">{{$size}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="select_draft draft_round draft-round">
                            <h4 style="width:100%">Draft Round</h4>
                            <p>(Total number of roster positions on each team)</p>
                            <!-- <ul class="list-unstyled list-inline">
                                    <li class="list-inline-item">
                                        <div class="form-group">
                                            <select name="draft_round">
                                                @foreach(Config::get('rounds') as $round)
                                                    <option class="text-dark" value="{{$round}}">{{$round}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                   
                                </ul> -->
                            <!-- ROSTER WORK FROM HERE TO START -->
                            <div class="rosterSetting">
                                <h5>Roster Setting</h5>
                                <br>
                                <center>
                                    <P>Roster Spots & Draft Rounds:14</P>
                                </center>
                                <div class="colorPickerDiv">
                                    <div class="incrementNumber">
                                        <button type="button" class="minusBtn">-</button>
                                        <input type="text" name="posrow[]" value="1" min="0">
                                        <button type="button" class="plusBtn" data-id="1">+</button>
                                    </div>
                                    <div class="colorPicker">
                                        <button type="button" style="background:black;color:white;border:1px solid;" data-button-id="1" class="PickSelector"></button>
                                        <input type="hidden" name="favcolor[]" data-color-id="1" value="#000" />
                                        <input type="color" disabled class="favcolor" id="colorInput" data-id="1" value="0">
                                    </div>
                                    <input type="hidden" name="order[]" value="1" id="order1" />
                                    <input type="hidden" name="pos[]" value="QB" id="posid1" />
                                    <p>QUARTERBACK (QB)</p>
                                </div>
                                <div class="colorPickerDiv">
                                    <div class="incrementNumber">
                                        <button type="button" class="minusBtn">-</button>
                                        <input type="text" name="posrow[]" value="2" min="0">
                                        <button type="button" class="plusBtn" data-id="2">+</button>
                                    </div>
                                    <div class="colorPicker">
                                        <button type="button" style="background:black;color:white;border:1px solid;" data-button-id="2" class="PickSelector"></button>
                                        <input type="hidden" name="favcolor[]" data-color-id="2" value="#000" />
                                        <input class="favcolor" disabled data-id="2" id="colorInput" type="color" value="">
                                    </div>
                                    <input type="hidden" name="order[]" value="2" id="order2" />
                                    <input type="hidden" name="pos[]" value="RB" id="posid2" />
                                    <p>RUNNING BACK (RB)</p>
                                </div>
                                <div class="colorPickerDiv">
                                    <div class="incrementNumber">
                                        <button type="button" class="minusBtn">-</button>
                                        <input type="text" name="posrow[]" value="3" min="0">
                                        <button type="button" class="plusBtn" data-id="3">+</button>
                                    </div>
                                    <div class="colorPicker">
                                        <button type="button" style="background:black;color:white;border:1px solid;" data-button-id="3" class="PickSelector"></button>
                                        <input type="hidden" name="favcolor[]" data-color-id="3" value="#000" />
                                        <input class="favcolor" disabled data-id="3" id="colorInput" type="color" value="">
                                    </div>
                                    <input type="hidden" name="order[]" value="3" id="order3" />
                                    <input type="hidden" name="pos[]" value="WR" id="posid3" />
                                    <p>WIDE RECEIVER (WR)</p>
                                </div>
                                <div class="colorPickerDiv">
                                    <div class="incrementNumber">
                                        <button type="button" class="minusBtn">-</button>
                                        <input type="text" name="posrow[]" value="1" min="0">
                                        <button type="button" class="plusBtn" data-id="4">+</button>
                                    </div>
                                    <div class="colorPicker">
                                        <button type="button" style="background:black;color:white;border:1px solid;" data-button-id="4" class="PickSelector"></button>
                                        <input type="hidden" name="favcolor[]" data-color-id="4" value="#000" />
                                        <input class="favcolor" disabled data-id="4" id="colorInput" type="color" value="">
                                    </div>
                                    <input type="hidden" name="order[]" value="4" id="order4" />
                                    <input type="hidden" name="pos[]" value="TE" id="posid4" />
                                    <p>TIGHT END (TE)</p>
                                </div>
                                <div class="colorPickerDiv">
                                    <div class="incrementNumber">
                                        <button type="button" class="minusBtn">-</button>
                                        <input type="text" name="posrow[]" value="0" min="0">
                                        <button type="button" class="plusBtn" data-id="5">+</button>
                                    </div>
                                    <div class="colorPicker">
                                        <button type="button" style="background:black;color:white;border:1px solid;;" data-button-id="5" class="PickSelector"></button>
                                        <input type="hidden" name="favcolor[]" data-color-id="5" value="#000" />
                                        <input class="favcolor" disabled data-id="5" id="colorInput" type="color" value="">
                                    </div>
                                    <input type="hidden" name="order[]" value="5" id="order5" />
                                    <input type="hidden" name="pos[]" value="WRT" id="posid5" />
                                    <p>(FLEX (WR/RB/TE))</p>
                                </div>
                                <div class="colorPickerDiv">
                                    <div class="incrementNumber">
                                        <button type="button" class="minusBtn">-</button>
                                        <input type="text" name="posrow[]" value="0" min="0">
                                        <button type="button" class="plusBtn" data-id="13">+</button>
                                    </div>
                                    <div class="colorPicker">
                                        <button type="button" style="background:black;color:white;border:1px solid;;" data-button-id="13" class="PickSelector"></button>
                                        <input type="hidden" name="favcolor[]" data-color-id="13" value="#000" />
                                        <input class="favcolor" disabled data-id="13" id="colorInput" type="color" value="">
                                    </div>
                                    <input type="hidden" name="order[]" value="6" id="order13" />
                                    <input type="hidden" name="pos[]" value="WR/TE" id="posid13" />
                                    <p>FLEX (WR/TE)</p>
                                </div>
                                <div class="colorPickerDiv">
                                    <div class="incrementNumber">
                                        <button type="button" class="minusBtn">-</button>
                                        <input type="text" name="posrow[]" value="0" min="0">
                                        <button type="button" class="plusBtn" data-id="14">+</button>
                                    </div>
                                    <div class="colorPicker">
                                        <button type="button" style="background:black;color:white;border:1px solid;;" data-button-id="14" class="PickSelector"></button>
                                        <input type="hidden" name="favcolor[]" data-color-id="14" value="#000" />
                                        <input class="favcolor" disabled data-id="14" id="colorInput" type="color" value="">
                                    </div>
                                    <input type="hidden" name="order[]" value="7" id="order14" />
                                    <input type="hidden" name="pos[]" value="WR/RB" id="posid14" />
                                    <p>FLEX (WR/RB)</p>
                                </div>
                                <div class="colorPickerDiv">
                                    <div class="incrementNumber">
                                        <button type="button" class="minusBtn">-</button>
                                        <input type="text" name="posrow[]" value="0" min="0">
                                        <button type="button" class="plusBtn" data-id="15">+</button>
                                    </div>
                                    <div class="colorPicker">
                                        <button type="button" style="background:black;color:white;border:1px solid;;" data-button-id="15" class="PickSelector"></button>
                                        <input type="hidden" name="favcolor[]" data-color-id="15" value="#000" />
                                        <input class="favcolor" disabled data-id="15" id="colorInput" type="color" value="0">
                                    </div>
                                    <input type="hidden" name="order[]" value="8" id="order15" />
                                    <input type="hidden" name="pos[]" value="QB/WR/RB/TE" id="posid15" />
                                    <p>FLEX (QB/WR/RB/TE)</p>
                                </div>
                                <div class="colorPickerDiv">
                                    <div class="incrementNumber">
                                        <button type="button" class="minusBtn">-</button>
                                        <input type="text" name="posrow[]" value="1" min="0">
                                        <button type="button" class="plusBtn" data-id="6">+</button>
                                    </div>
                                    <div class="colorPicker">
                                        <button type="button" style="background:black;color:white;border:1px solid;;" data-button-id="6" class="PickSelector"></button>
                                        <input type="hidden" name="favcolor[]" data-color-id="6" value="#000" />
                                        <input class="favcolor" disabled data-id="6" id="colorInput" type="color" value="">
                                    </div>
                                    <input type="hidden" name="order[]" value="9" id="order6" />
                                    <input type="hidden" name="pos[]" value="K" id="posid6" />
                                    <p>K</p>
                                </div>
                                <div class="colorPickerDiv">
                                    <div class="incrementNumber">
                                        <button type="button" class="minusBtn">-</button>
                                        <input type="text" name="posrow[]" value="1" min="0">
                                        <button type="button" class="plusBtn" data-id="7">+</button>
                                    </div>
                                    <div class="colorPicker">
                                        <button type="button" style="background:black;color:white;border:1px solid;;" data-button-id="7" class="PickSelector"></button>
                                        <input type="hidden" name="favcolor[]" data-color-id="7" value="#000" />
                                        <input class="favcolor" disabled data-id="7" id="colorInput" type="color" value="">
                                    </div>
                                    <input type="hidden" name="order[]" value="10" id="order7" />
                                    <input type="hidden" name="pos[]" value="DEF" id="posid7" />
                                    <p>DEF</p>
                                </div>
                                <div class="colorPickerDiv">
                                    <div class="incrementNumber">
                                        <button type="button" class="minusBtn">-</button>
                                        <input type="text" name="posrow[]" value="5" min="0">
                                        <button type="button" class="plusBtn" data-id="12">+</button>
                                    </div>
                                    <div class="colorPicker">
                                        <button type="button" style="background:black;color:white;border:1px solid;" data-button-id="12" class="PickSelector"></button>
                                        <input type="hidden" name="favcolor[]" data-color-id="12" value="#000" />
                                        <input class="favcolor" disabled data-id="12" id="colorInput" type="color" value="">
                                    </div>
                                    <input type="hidden" name="order[]" value="11" id="order12" />
                                    <input type="hidden" name="pos[]" value="BENCH" id="posid12" />
                                    <p>Bench</p>
                                </div>
                                <div class="colorPickerDiv">
                                    <div class="incrementNumber">
                                        <button type="button" class="minusBtn">-</button>
                                        <input type="text" name="posrow[]" value="0" min="0">
                                        <button type="button" class="plusBtn" data-id="8">+</button>
                                    </div>
                                    <div class="colorPicker">
                                        <button type="button" style="background:black;color:white;border:1px solid;" data-button-id="8" class="PickSelector"></button>
                                        <input type="hidden" name="favcolor[]" data-color-id="8" value="#000" />
                                        <input class="favcolor" disabled data-id="8" id="colorInput" type="color" value="">
                                    </div>
                                    <input type="hidden" name="order[]" value="12" id="order8" />
                                    <input type="hidden" name="pos[]" value="DL" id="posid8" />
                                    <p>DL</p>
                                </div>
                                <div class="colorPickerDiv">
                                    <div class="incrementNumber">
                                        <button type="button" class="minusBtn">-</button>
                                        <input type="text" name="posrow[]" value="0" min="0">
                                        <button type="button" class="plusBtn" data-id="9">+</button>
                                    </div>
                                    <div class="colorPicker">
                                        <button type="button" style="background:black;color:white;border:1px solid;" data-button-id="9" class="PickSelector"></button>
                                        <input type="hidden" name="favcolor[]" data-color-id="9" value="#000" />
                                        <input class="favcolor" disabled data-id="9" id="colorInput" type="color" value="">
                                    </div>
                                    <input type="hidden" name="order[]" value="13" id="order9" />
                                    <input type="hidden" name="pos[]" value="LB" id="posid9" />
                                    <p>LB</p>
                                </div>
                                <div class="colorPickerDiv">
                                    <div class="incrementNumber">
                                        <button type="button" class="minusBtn">-</button>
                                        <input type="text" name="posrow[]" value="0" min="0">
                                        <button type="button" class="plusBtn">+</button>
                                    </div>
                                    <div class="colorPicker">
                                        <button type="button" style="background:black;color:white;border:1px solid;" data-button-id="11" class="PickSelector"></button>
                                        <input type="hidden" name="favcolor[]" data-color-id="11" value="#000" />
                                        <input class="favcolor" disabled data-id="11" id="colorInput" type="color" value="">
                                    </div>
                                    <input type="hidden" name="order[]" value="14" />
                                    <input type="hidden" name="pos[]" value="DB" />
                                    <p>DB</p>
                                </div>
                                <div class="colorPickerDiv">
                                    <div class="incrementNumber">
                                        <button type="button" class="minusBtn">-</button>
                                        <input type="text" name="posrow[]" value="0" min="0">
                                        <button type="button" class="plusBtn">+</button>
                                    </div>
                                    <div class="colorPicker">
                                        <button type="button" style="background:black;color:white;border:1px solid;" data-button-id="10" class="PickSelector"></button>
                                        <input type="hidden" name="favcolor[]" data-color-id="10" value="#000" />
                                        <input class="favcolor" disabled data-id="10" id="colorInput" type="color" value="">
                                    </div>
                                    <input type="hidden" name="order[]" value="15" />
                                    <input type="hidden" name="pos[]" value="IDP" />
                                    <p>IDP</p>
                                </div>
                            </div>
                            <br>
                            <!-- END OF ROSTER WORK -->
                            <ul class="list-unstyled list-inline">
                                <li class="list-inline-item">
                                    <button type="submit">Build Draft Board</button>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="col-md-6">

                    <!-- <div class="col-md-6">
                        <div class="draft_picks">
                            <h4>Import Draft Picks, Rosters </br> & League Settings</h4>
                            <div class="yahoo">
                                <img src="{{ asset('images/yahoo.jpg') }}">
                            </div>

                            <div class="skip">
                                <a href="#">Skip & Go To Draft Board</a>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/league/create.js') }}"></script>
<script>
    $(".plusBtn").click(function() {
        id = $(this).attr('data-id');
        pos = $("#posid" + id + "").val();
        val = parseInt($(this).parent().find("input").val());
        color = $(this).parent().next().find("input").val();
        orderno = $("#order" + id + "").val();
        val = val + 1;
        $(this).parent().find("input").val(val);
    })
    $(".minusBtn").click(function() {
        val = parseInt($(this).parent().find("input").val());
        val = val - 1;
        if (val < 0) {
            $(this).parent().find("input").val(0);
        } else {
            $(this).parent().find("input").val(val);
        }

    })

    //$(".colorPickSelector").colorPick();
    $(".PickSelector").colorPick({
        // 'initialColor': '#3498db',
        'allowRecent': true,
        'recentMax': 5,
        'allowCustomColor': false,
        'palette': ["#1abc9c", "#16a085", "#2ecc71", "#27ae60", "#3498db", "#2980b9", "#9b59b6", "#8e44ad", "#34495e", "#2c3e50", "#f1c40f", "#f39c12", "#e67e22", "#d35400", "#e74c3c", "#c0392b", "#ecf0f1", "#bdc3c7", "#95a5a6", "#7f8c8d"],
        'onColorSelected': function() {
            // this.element.css({
            // 	'backgroundColor': this.color,
            // 	'color': this.color
            // });
            if (this.color != '#000XXX') {
                this.element.css('background', this.color);
                id = this.element.attr('data-button-id');
                color = this.color;
                $("[data-id='" + id + "']").val(this.color);
                $("[data-color-id='" + id + "']").val(this.color);

            }

        }
    });
</script>
@endsection