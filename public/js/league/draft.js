var leagueId = $("input[name='league_id']").val();
$(document).ready(function () {
    setTimeout(function () {
        //calls click event after a certain time
        if ($(".dropDownDiv").css("display") == "block") {
            $(".city_board_table").css("margin-top", "563px");
        } else {
            console.log("hello");
            $(".city_board_table").css("margin-top", "0px");
            $(".city_board_table table").css("margin-top", "325px");
        }
    }, 1000);
});
$(".modal").css(
    "top",
    $(window).outerHeight() / 2 - $(".modal-dialog").outerHeight() / 2 + "px"
);
$(function () {
    var projects = [
        {
            value: "java",
            label: "Java",
            desc: "write once run anywhere",
        },
        {
            value: "jquery-ui",
            label: "jQuery UI",
            desc: "the official user interface library for jQuery",
        },
        {
            value: "Bootstrap",
            label: "Twitter Bootstrap",
            desc: "popular front end frameworks ",
        },
    ];
    $("#project")
        .autocomplete({
            minLength: 0,
            source: projects,
            focus: function (event, ui) {
                $("#project").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#project").val(ui.item.label);
                $("#project-id").val(ui.item.value);
                $("#project-description").html(ui.item.desc);
                return false;
            },
        })

        .data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.label + "<br>" + item.desc + "</a>")
            .appendTo(ul);
    };
});
$(function () {
    var val = "";
    $(".draftButton").click(function () {
        // savePick(val);
        val = $("#myInput2").attr("data-id");
        if (val != "") {
            savePick(val);
            timerSettings($("#timerBtn"), "refresh");
            timerSettings($("#timerBtn"), "start");
            val = "";
            window.location =
                "/league/" + $("input[name='league_id']").val() + "/draft";
        }
    });
    $("#saveKeeper").click(function () {
        // savePick(val);
        val = $("#myInput").attr("data-id");
        if (val != "") {
            savePick(val);
            val = "";
            window.location =
                "/league/" + $("input[name='league_id']").val() + "/draft";
        }
    });

    $("#saveKeeperlist").click(function () {
        if ($("#keeperlistround").val() == "") {
            alert("plese enter round number");
            return false;
        } else {
            val = $("#myInput3").attr("data-id");
            savekeeperlist(
                val,
                $("#keeperlistround").val(),
                $("#keeperlistteamid").val(),
                leagueId
            );
            window.location =
                "/league/" +
                $("input[name='league_id']").val() +
                "/draft?type=keeperlist";
        }
    });
    $("#updateKeeperlist").click(function () {
        if ($("#editkeeperlistround").val() == "") {
            alert("plese enter round number");
            return false;
        } else {
            val = $("#myInput4").attr("data-id");
            if (!val) {
                val = $("#oldplayerid").val();
            }
            updatekeeperlist(
                val,
                $("#oldplayerid").val(),
                $("#editkeeperlistround").val(),
                $("#editkeeperlistteamid").val(),
                leagueId
            );
            // window.location =
            //     "/league/" +
            //     $("input[name='league_id']").val() +
            //     "/draft?type=keeperlist";
        }
    });
    //keeperlist button save work
    $(".keeperlistbutton").click(function () {
        if ($("#keeperlistround").val() == "") {
            alert("plese enter round number");
            return false;
        }
        val = $("#myInput3").attr("data-id");
        teamid = $("#keeperlistteamid").val();
        round_number = $("#keeperlistround").val();
        if (val != "") {
            $.ajax({
                url: "/league/" + leagueId + "/savekeeperlist",
                method: "POST",
                data: { id: val, teamid: teamid, round_number: round_number },
                success: function (res) {
                    if (res.status == "success") {
                        document.getElementById("playerBeep").play();
                        window.location =
                            "/league/" +
                            $("input[name='league_id']").val() +
                            "/draft?type=keeperlist";
                    } else if (res.status == "error") {
                        alert(res.message);
                    }
                },
            });
        }
    });
    //remove keeper list
    $(document).on("click", "#removekeeperlist", function () {
        val = $("#myInput4").attr("data-id");
        round_number = $("#editkeeperlistround").val();
        teamid = $("#editkeeperlistteamid").val();
        if (val != "") {
            $.ajax({
                url: "/league/" + leagueId + "/removekeeperlist",
                method: "POST",
                data: {
                    id: val,
                    teamid: teamid,
                    round_number: round_number,
                },
                success: function (res) {
                    if (res == "success") {
                        document.getElementById("playerBeep").play();
                        window.location =
                            "/league/" +
                            $("input[name='league_id']").val() +
                            "/draft?type=keeperlist";
                    } else if (res == "error") {
                        alert("something went wrong");
                    }
                },
            });
        }
    });

    $(".draftPlayer").select2();
    $(".keeperPlayer").select2({
        dropdownParent: $("#keeperModal"),
    });
    $(document).on("keyup", ".select2-search__field", function () {
        console.log("eheere");
        $(".select2-results").css("display", "block");
        $(".select2-container--open .select2-dropdown").css("height", "auto");
    });
    $(".draftPlayer").on("change", function () {
        val = $(this).val();
    });
    //my work here obaid
    $(document).on("change", ".teamselect", function () {
        $teamdata = $(this).val();
        view = $(this).attr("data-view");
        $.ajax({
            type: "get",
            url: "/changeTeam",
            data: { teamdata: $teamdata },
            success: function (response) {
                //console.log(response);
                if (view == "collapse") {
                    window.location =
                        "/league/" +
                        $("input[name='league_id']").val() +
                        "/draft?type=collapseview";
                } else {
                    window.location =
                        "/league/" +
                        $("input[name='league_id']").val() +
                        "/draft";
                }
            },
        });
    });

    $(document).on("click", "#removePlayer", function () {
        // console.log($(this)[0].attr;
        player_id = $(this).attr("data-player_id");
        round_id = $(this).attr("data-round_id");
        team_id = $(this).attr("data-team_id");
        league_id = $(this).attr("data-league_id");

        $.ajax({
            type: "get",
            url: "/removePlayer",
            data: {
                player_id: player_id,
                round_id: round_id,
                team_id: team_id,
                league_id: league_id,
            },
            success: function (response) {
                console.log(response);
                window.location = "/league/" + league_id + "/draft";
            },
        });
    });

    $(".undoPick").on("click", "#undoBtn", async function () {
        let lastPick = $(".undoPick #undoBtn");
        let confirmation = await confirmationDiv(lastPick);
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this pick!",
            content: confirmation,
            buttons: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "POST",
                    url:
                        "/league/" +
                        leagueId +
                        "/draft/pick/delete/" +
                        lastPick.attr("round_id"),
                    success: function (response) {
                        if (response.status == 200) {
                            successMessage("Pick deleted successfully!");
                            $(
                                "td[data-round_id='" +
                                    lastPick.attr("round_id") +
                                    "']"
                            ).text(
                                $(
                                    "td[data-round_id='" +
                                        lastPick.attr("round_id") +
                                        "']"
                                ).attr("data-default_order")
                            );
                            if (response.data.league_round == null) {
                                $(".undoPick").addClass("hide");
                            } else {
                                let playerName =
                                    lastPick.attr("last_name") +
                                    " " +
                                    lastPick.attr("first_name") +
                                    " " +
                                    lastPick.attr("position");
                                let newOption = new Option(
                                    playerName,
                                    lastPick.attr("player_id"),
                                    false,
                                    false
                                );
                                $(".draftPlayer")
                                    .append(newOption)
                                    .trigger("change");
                                updateLastPick(
                                    response.data.league_round,
                                    response.data.counts
                                );
                                if ($(".draftPlayerLi").hasClass("hide")) {
                                    $(".draftPlayerLi").removeClass("hide");
                                }
                            }
                        } else {
                            errorMessage(
                                "Something went wrong. Please try again later."
                            );
                        }
                    },
                });
            }
        });
    });

    $(".addKeeper").on("click", function () {
        $('input[name="round_id"]').val($(this).parent("td").data("round_id"));
        $("#keeperModal").modal("toggle");
        $("#myInput2").attr("round-order", $(this).attr("round-order"));
        $("#myInput2").attr("round-number", $(this).attr("round-number"));
    });
    //new work for keeper list button here
    $(".addKeeperlist").on("click", function () {
        team_id = $(this).attr("data-team-id");
        $("#keeperlistteamid").val(team_id);
        $("#keeperlistModal").modal("toggle");
    });

    // $('#saveKeeper').on('click', function () {
    //   savePick($(".keeperPlayer option:selected").val(), $('input[name="round_id"]').val(), 'keeper');
    // });

    $(".draftStatus").on("click", function (e) {
        e.preventDefault();
        let status = $(this).data("type") == "keeper" ? "started" : "keeper";
        changeLeagueStatus(status);
    });
});

function changeLeagueStatus(status) {
    $.ajax({
        type: "POST",
        url: "/league/" + $("input[name='league_id']").val() + "/changeStatus",
        data: { status: status },
        success: function (response) {
            window.location =
                "/league/" + $("input[name='league_id']").val() + "/draft";
        },
    });
}

function savePick(playerId, roundId = 0, type = "draft") {
    // let selectClass = type == 'draft' ? 'draftPlayer' : 'keeperPlayer';
    // let playerLastName = $("." + selectClass + " option:selected").data('last_name');
    // let playerFirstName = $("." + selectClass + " option:selected").data('first_name');
    // let position = $("." + selectClass + " option:selected").data('position');
    // let player_team = $("." + selectClass + " option:selected").data('team');
    leagueId = $("input[name='league_id']").val();
    let playerLastName = $("#myInput2").attr("data-last_name");
    let playerFirstName = $("#myInput2").attr("data-first_name");
    let position = $("#myInput2").attr("data-pos");
    let player_team = $("#myInput2").attr("data-team");
    let round_order = $("#myInput2").attr("round-order");
    let round_number = $("#myInput2").attr("round-number");
    if (playerId) {
        $.ajax({
            type: "POST",
            url: "/league/" + leagueId + "/savePick",
            data: {
                player_id: playerId,
                round_id: roundId,
                round_order: round_order,
                round_number: round_number,
            },
            success: function (response) {
                console.log(response);
                if (response.status == 200) {
                    successMessage(response.message);
                    $(".select2Drp").select2("val", "");
                    $(".select2Drp option[value='" + playerId + "']").remove();
                    // var team_name = $("td[data-round_id='"+response.data.round_id+"']").children()[0].textContent;

                    //obaid work here
                    $("#team-round").html(
                        "TEAM " + (response.data.league_round.round_id + 1)
                    );
                    $("#upNext").html(
                        "Up Next: Team " +
                            (response.data.league_round.round_id + 2)
                    );
                    $("#team-select").html(
                        "TEAM " +
                            response.data.league_round.round_id +
                            " Selects"
                    );
                    $("#team-slect-fname").html(playerFirstName);
                    $("#team-slect-lname").html(playerLastName);

                    //
                    var team = "";
                    for (
                        var i = 0;
                        i < response.data.leagueteam.teams.length;
                        i++
                    ) {
                        if (
                            response.data.leagueteam.teams[i].team_name ==
                            response.data.league_round.team_name
                        ) {
                            selected = "selected";
                        } else {
                            selected = "";
                        }
                        team +=
                            "<option value=" +
                            response.data.leagueteam.teams[i].id +
                            "|" +
                            response.data.league_round.round_number +
                            "|" +
                            response.data.leagueid +
                            "|" +
                            response.data.league_round.round_id +
                            " " +
                            selected +
                            ">" +
                            response.data.leagueteam.teams[i].team_name +
                            "</option>";
                    }
                    if (round_order) {
                        $(
                            "td[data-round_id='" +
                                response.data.nround_id +
                                "']"
                        ).children()[0].innerHTML =
                            '<select id="teamselect" class="teamselect" name="teamselect" style="padding:9px 10px 8px 0px !important;">' +
                            team +
                            '</select><br><span style="font-size:13px;float: left;padding: 10px;">' +
                            position +
                            '</span ><span style="float: right;padding: 10px;font-size:13px;"> ' +
                            player_team +
                            ' </span><br><div class="team_info"><span style="font-size:13px;">' +
                            playerFirstName +
                            "</span>" +
                            '<br><span style="font-weight:bold;font-size:22px;">' +
                            playerLastName +
                            "</span></div> ";
                    } else {
                        $(
                            "td[data-round_id='" + response.data.round_id + "']"
                        ).children()[0].innerHTML =
                            '<select id="teamselect" name="teamselect" style="padding:9px 10px 8px 0px !important;">' +
                            team +
                            '</select><br><span style="font-size:13px;float: left;padding: 10px;">' +
                            position +
                            '</span ><span style="float: right;padding: 10px;font-size:13px;"> ' +
                            player_team +
                            ' </span><br><div class="team_info"><span style="font-size:13px;">' +
                            playerFirstName +
                            "</span>" +
                            '<br><span style="font-weight:bold;font-size:22px;">' +
                            playerLastName +
                            "</span></div> ";
                    }
                    if (type == "draft") {
                        //$("td[data-round_id='"+response.data.round_id+"']").text(playerLastName);
                        if ($(".undoPick").hasClass("hide")) {
                            $(".undoPick").removeClass("hide");
                        }
                        let leagueRound = response.data.league_round;
                        leagueRound.first_name = playerFirstName;
                        leagueRound.last_name = playerLastName;
                        leagueRound.position = position;
                        updateLastPick(leagueRound, response.data.counts);
                        if (
                            response.data.counts &&
                            response.data.counts.without_player_count == 0
                        ) {
                            $(".draftPlayerLi").addClass("hide");
                        }
                    } else if (type == "keeper") {
                        //$("td[data-round_id='"+response.data.round_id+"']").text(playerLastName);
                        $("#keeperModal").modal("toggle");
                        $('input[name="round_id"]').val(0);
                    }
                    document.getElementById("playerBeep").play();
                } else {
                    errorMessage(response.message);
                }
            },
        });
    }
}

function savekeeperlist(playerId, roundId, teamid, leagueId) {
    roundorder = $("#roundorder").val();
    if (!roundorder) {
        roundorder = "";
    }
    if (playerId) {
        $.ajax({
            type: "POST",
            url: "/league/" + leagueId + "/saveroundkeeperlist",
            data: {
                playerId: playerId,
                roundId: roundId,
                teamid: teamid,
                roundorder: roundorder,
            },
            success: function (response) {
                if (response.status == "exist") {
                    alert(response.message);
                    return false;
                } else if (response == "success") {
                    document.getElementById("playerBeep").play();
                } else if (response.status == "error") {
                    alert(response.message);
                    return false;
                }
            },
        });
    }
}
function updatekeeperlist(playerId, oldplayerid, roundId, teamid, leagueId) {
    roundorder = $("#roundorder").val();
    if (!roundorder) {
        roundorder = "";
    }
    if (playerId) {
        $.ajax({
            type: "POST",
            url: "/league/" + leagueId + "/updateroundkeeperlist",
            data: {
                playerId: playerId,
                oldplayerid: oldplayerid,
                roundId: roundId,
                teamid: teamid,
                roundorder: roundorder,
            },
            success: function (response) {
                if (response.status == "success") {
                    document.getElementById("playerBeep").play();
                } else if (response.status == "error") {
                    alert(response.message);
                    return false;
                }
            },
        });
    }
}
function confirmationDiv(lastPick) {
    var div = document.createElement("div");
    div.innerHTML =
        '<table class="table table-striped">' +
        "<tbody>" +
        "<tr>" +
        "<td>Off Season Team</td>" +
        "<td>" +
        lastPick.attr("team_name") +
        "</td>" +
        "</tr>" +
        "<tr>" +
        "<td>Player</td>" +
        "<td>" +
        lastPick.attr("last_name") +
        " " +
        lastPick.attr("first_name") +
        " " +
        lastPick.attr("position") +
        "</td>" +
        "</tr>" +
        "<tr>" +
        "<td>Overall Pick</td>" +
        "<td>" +
        lastPick.attr("overall_pick") +
        "</td>" +
        "</tr>" +
        "<tr>" +
        "<td>Round</td>" +
        "<td>" +
        lastPick.attr("round_number") +
        "</td>" +
        "</tr>" +
        "<tr>" +
        "<td>Pick</td>" +
        "<td>" +
        lastPick.attr("pick_number") +
        "</td>" +
        "</tr>" +
        "</tbody>" +
        "</table>";
    return div;
}

function updateLastPick(leagueRound, counts) {
    $(".undoPick #lastName").text(leagueRound.last_name);
    $(".undoPick #firstName").text(leagueRound.first_name);
    $(".undoPick #playerPosition").text(leagueRound.position);
    $(".undoPick #undoBtn")
        .attr("last_name", leagueRound.last_name)
        .attr("first_name", leagueRound.first_name)
        .attr("position", leagueRound.position)
        .attr("player_id", leagueRound.player_id)
        .attr("round_id", leagueRound.round_id)
        .attr("team_name", leagueRound.team_name)
        .attr("round_number", leagueRound.round_number)
        .attr("pick_number", leagueRound.pick_number)
        .attr(
            "overall_pick",
            counts.rounds_count - counts.without_player_count
        );
}

//function autocomplete(inp, arr, arr1) {
/*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
var currentFocus;
/*execute a function when someone writes in the text field:*/
// inp.addEventListener("input", function (e) {
var inp;
$("#myInput").on("keyup", function (e) {
    inp = document.getElementById("myInput");
    qparmas = $("#myInput").val();
    var leagid = $("input[name='league_id']").val();
    var arr = [];
    var arr1 = [];
    $.ajax({
        url: "/league/team",
        method: "get",
        async: false,
        data: { id: leagid, qparmas: qparmas },
        success: function (res) {
            res = JSON.parse(res);
            console.log(res);
            for (i = 0; i < res.length; i++) {
                arr.push(
                    res[i].first_name +
                        " " +
                        res[i].last_name +
                        " " +
                        res[i].team +
                        "/" +
                        res[i].position
                );
                arr1.push(
                    res[i].first_name +
                        "/" +
                        res[i].last_name +
                        "/" +
                        res[i].id +
                        "/" +
                        res[i].team +
                        "/" +
                        res[i].position
                );
            }
        },
    });

    var a,
        b,
        i,
        val = this.value;
    /*close any already open lists of autocompleted values*/
    closeAllLists();
    if (!val) {
        return false;
    }
    currentFocus = -1;
    /*create a DIV element that will contain the items (values):*/
    a = document.createElement("DIV");
    a.setAttribute("id", this.id + "autocomplete-list");
    a.setAttribute("class", "autocomplete-items");
    /*append the DIV element as a child of the autocomplete container:*/
    console.log(arr.length);
    this.parentNode.appendChild(a);
    /*for each item in the array...*/
    for (i = 0; i <= arr.length; i++) {
        //make first letter capital
        console.log(arr[i]);
        //myarr=new Array();
        //myarr[i]=arr[i];
        // if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase() ||  myarr[i].toUpperCase().indexOf(val.toUpperCase()) > -1) {
        /*create a DIV element for each matching element:*/
        b = document.createElement("DIV");
        /*make the matching letters bold:*/
        b.innerHTML += "<strong>" + arr[i] + "</strong>";
        //b.innerHTML += arr[i];

        /*insert a input field that will hold the current array item's value:*/
        var myArr = arr1[i].split("/");
        b.innerHTML +=
            "<input type='hidden'  value='" +
            arr[i] +
            "' data-first_name='" +
            myArr[0] +
            "' data-last_name='" +
            myArr[1] +
            "' data-id='" +
            myArr[2] +
            "' data-team='" +
            myArr[3] +
            "' data-pos='" +
            myArr[4] +
            "'>";
        /*execute a function when someone clicks on the item value (DIV element):*/
        b.addEventListener("click", function (e) {
            /*insert the value for the autocomplete text field:*/
            inp.value = this.getElementsByTagName("input")[0].value;
            first_name =
                this.getElementsByTagName("input")[0].getAttribute(
                    "data-first_name"
                );
            last_name =
                this.getElementsByTagName("input")[0].getAttribute(
                    "data-last_name"
                );
            playerid =
                this.getElementsByTagName("input")[0].getAttribute("data-id");
            playerteam =
                this.getElementsByTagName("input")[0].getAttribute("data-team");
            playerpos =
                this.getElementsByTagName("input")[0].getAttribute("data-pos");
            $("#myInput2").attr("value", inp.value);
            $("#myInput2").attr("data-first_name", first_name);
            $("#myInput2").attr("data-last_name", last_name);
            $("#myInput2").attr("data-id", playerid);
            $("#myInput2").attr("data-team", playerteam);
            $("#myInput2").attr("data-pos", playerpos);

            $("#myInput").attr("value", inp.value);
            $("#myInput").attr("data-first_name", first_name);
            $("#myInput").attr("data-last_name", last_name);
            $("#myInput").attr("data-id", playerid);
            $("#myInput").attr("data-team", playerteam);
            $("#myInput").attr("data-pos", playerpos);
            /*close the list of autocompleted values,
          (or any other open lists of autocompleted values:*/
            closeAllLists();
        });
        a.appendChild(b);
        // }
    }
});
/*execute a function presses a key on the keyboard:*/
//inp.addEventListener("keydown", function (e) {
$("#myInput").on("keydown", function (e) {
    var x = document.getElementById(this.id + "autocomplete-list");
    if (x) x = x.getElementsByTagName("div");
    if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
      increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
    } else if (e.keyCode == 38) {
        //up
        /*If the arrow UP key is pressed,
      decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
    } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
            /*and simulate a click on the "active" item:*/
            if (x) x[currentFocus].click();
        }
    }
});

//work for edit modal
$("#myInput2").on("keyup", function (e) {
    inp = document.getElementById("myInput2");
    qparmas = $("#myInput2").val();
    var leagid = $("input[name='league_id']").val();
    var arr = [];
    var arr1 = [];
    $.ajax({
        url: "/league/team",
        method: "get",
        async: false,
        data: { id: leagid, qparmas: qparmas },
        success: function (res) {
            res = JSON.parse(res);
            console.log(res);
            for (i = 0; i < res.length; i++) {
                arr.push(
                    res[i].first_name +
                        " " +
                        res[i].last_name +
                        " " +
                        res[i].team +
                        "/" +
                        res[i].position
                );
                arr1.push(
                    res[i].first_name +
                        "/" +
                        res[i].last_name +
                        "/" +
                        res[i].id +
                        "/" +
                        res[i].team +
                        "/" +
                        res[i].position
                );
            }
        },
    });

    var a,
        b,
        i,
        val = this.value;
    /*close any already open lists of autocompleted values*/
    closeAllLists();
    if (!val) {
        return false;
    }
    currentFocus = -1;
    /*create a DIV element that will contain the items (values):*/
    a = document.createElement("DIV");
    a.setAttribute("id", this.id + "autocomplete-list");
    a.setAttribute("class", "autocomplete-items");
    /*append the DIV element as a child of the autocomplete container:*/
    console.log(arr.length);
    this.parentNode.appendChild(a);
    /*for each item in the array...*/
    for (i = 0; i <= arr.length; i++) {
        //make first letter capital
        console.log(arr[i]);
        //myarr=new Array();
        //myarr[i]=arr[i];
        // if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase() ||  myarr[i].toUpperCase().indexOf(val.toUpperCase()) > -1) {
        /*create a DIV element for each matching element:*/
        b = document.createElement("DIV");
        /*make the matching letters bold:*/
        b.innerHTML += "<strong>" + arr[i] + "</strong>";
        //b.innerHTML += arr[i];

        /*insert a input field that will hold the current array item's value:*/
        var myArr = arr1[i].split("/");
        b.innerHTML +=
            "<input type='hidden'  value='" +
            arr[i] +
            "' data-first_name='" +
            myArr[0] +
            "' data-last_name='" +
            myArr[1] +
            "' data-id='" +
            myArr[2] +
            "' data-team='" +
            myArr[3] +
            "' data-pos='" +
            myArr[4] +
            "'>";
        /*execute a function when someone clicks on the item value (DIV element):*/
        b.addEventListener("click", function (e) {
            /*insert the value for the autocomplete text field:*/
            inp.value = this.getElementsByTagName("input")[0].value;
            first_name =
                this.getElementsByTagName("input")[0].getAttribute(
                    "data-first_name"
                );
            last_name =
                this.getElementsByTagName("input")[0].getAttribute(
                    "data-last_name"
                );
            playerid =
                this.getElementsByTagName("input")[0].getAttribute("data-id");
            playerteam =
                this.getElementsByTagName("input")[0].getAttribute("data-team");
            playerpos =
                this.getElementsByTagName("input")[0].getAttribute("data-pos");
            $("#myInput2").attr("value", inp.value);
            $("#myInput2").attr("data-first_name", first_name);
            $("#myInput2").attr("data-last_name", last_name);
            $("#myInput2").attr("data-id", playerid);
            $("#myInput2").attr("data-team", playerteam);
            $("#myInput2").attr("data-pos", playerpos);

            $("#myInput").attr("value", inp.value);
            $("#myInput").attr("data-first_name", first_name);
            $("#myInput").attr("data-last_name", last_name);
            $("#myInput").attr("data-id", playerid);
            $("#myInput").attr("data-team", playerteam);
            $("#myInput").attr("data-pos", playerpos);
            /*close the list of autocompleted values,
          (or any other open lists of autocompleted values:*/
            closeAllLists();
        });
        a.appendChild(b);
        // }
    }
});
/*execute a function presses a key on the keyboard:*/
//inp.addEventListener("keydown", function (e) {
$("#myInput2").on("keydown", function (e) {
    var x = document.getElementById(this.id + "autocomplete-list");
    if (x) x = x.getElementsByTagName("div");
    if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
      increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
    } else if (e.keyCode == 38) {
        //up
        /*If the arrow UP key is pressed,
      decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
    } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
            /*and simulate a click on the "active" item:*/
            if (x) x[currentFocus].click();
        }
    }
});
$("#myInput3").on("keyup", function (e) {
    inp = document.getElementById("myInput3");
    qparmas = $("#myInput3").val();
    var leagid = $("input[name='league_id']").val();
    var arr = [];
    var arr1 = [];
    $.ajax({
        url: "/league/team",
        method: "get",
        async: false,
        data: { id: leagid, qparmas: qparmas, myinput3: "myinput3" },
        success: function (res) {
            res = JSON.parse(res);
            console.log(res);
            for (i = 0; i < res.length; i++) {
                arr.push(
                    res[i].first_name +
                        " " +
                        res[i].last_name +
                        " " +
                        res[i].team +
                        "/" +
                        res[i].position
                );
                arr1.push(
                    res[i].first_name +
                        "/" +
                        res[i].last_name +
                        "/" +
                        res[i].id +
                        "/" +
                        res[i].team +
                        "/" +
                        res[i].position
                );
            }
        },
    });

    var a,
        b,
        i,
        val = this.value;
    /*close any already open lists of autocompleted values*/
    closeAllLists();
    if (!val) {
        return false;
    }
    currentFocus = -1;
    /*create a DIV element that will contain the items (values):*/
    a = document.createElement("DIV");
    a.setAttribute("id", this.id + "autocomplete-list");
    a.setAttribute("class", "autocomplete-items");
    /*append the DIV element as a child of the autocomplete container:*/
    console.log(arr.length);
    this.parentNode.appendChild(a);
    /*for each item in the array...*/
    for (i = 0; i <= arr.length; i++) {
        //make first letter capital
        console.log(arr[i]);
        //myarr=new Array();
        //myarr[i]=arr[i];
        // if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase() ||  myarr[i].toUpperCase().indexOf(val.toUpperCase()) > -1) {
        /*create a DIV element for each matching element:*/
        b = document.createElement("DIV");
        /*make the matching letters bold:*/
        b.innerHTML += "<strong>" + arr[i] + "</strong>";
        //b.innerHTML += arr[i];

        /*insert a input field that will hold the current array item's value:*/
        var myArr = arr1[i].split("/");
        b.innerHTML +=
            "<input type='hidden'  value='" +
            arr[i] +
            "' data-first_name='" +
            myArr[0] +
            "' data-last_name='" +
            myArr[1] +
            "' data-id='" +
            myArr[2] +
            "' data-team='" +
            myArr[3] +
            "' data-pos='" +
            myArr[4] +
            "'>";
        /*execute a function when someone clicks on the item value (DIV element):*/
        b.addEventListener("click", function (e) {
            /*insert the value for the autocomplete text field:*/
            inp.value = this.getElementsByTagName("input")[0].value;
            first_name =
                this.getElementsByTagName("input")[0].getAttribute(
                    "data-first_name"
                );
            last_name =
                this.getElementsByTagName("input")[0].getAttribute(
                    "data-last_name"
                );
            playerid =
                this.getElementsByTagName("input")[0].getAttribute("data-id");
            playerteam =
                this.getElementsByTagName("input")[0].getAttribute("data-team");
            playerpos =
                this.getElementsByTagName("input")[0].getAttribute("data-pos");
            $("#myInput3").attr("value", inp.value);
            $("#myInput3").attr("data-first_name", first_name);
            $("#myInput3").attr("data-last_name", last_name);
            $("#myInput3").attr("data-id", playerid);
            $("#myInput3").attr("data-team", playerteam);
            $("#myInput3").attr("data-pos", playerpos);

            $("#myInput").attr("value", inp.value);
            $("#myInput").attr("data-first_name", first_name);
            $("#myInput").attr("data-last_name", last_name);
            $("#myInput").attr("data-id", playerid);
            $("#myInput").attr("data-team", playerteam);
            $("#myInput").attr("data-pos", playerpos);
            /*close the list of autocompleted values,
          (or any other open lists of autocompleted values:*/
            closeAllLists();
        });
        a.appendChild(b);
        // }
    }
});
/*execute a function presses a key on the keyboard:*/
//inp.addEventListener("keydown", function (e) {
$("#myInput3").on("keydown", function (e) {
    var x = document.getElementById(this.id + "autocomplete-list");
    if (x) x = x.getElementsByTagName("div");
    if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
      increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
    } else if (e.keyCode == 38) {
        //up
        /*If the arrow UP key is pressed,
      decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
    } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
            /*and simulate a click on the "active" item:*/
            if (x) x[currentFocus].click();
        }
    }
});

$("#myInput4").on("keyup", function (e) {
    inp = document.getElementById("myInput4");
    qparmas = $("#myInput4").val();
    var leagid = $("input[name='league_id']").val();
    var arr = [];
    var arr1 = [];
    $.ajax({
        url: "/league/team",
        method: "get",
        async: false,
        data: { id: leagid, qparmas: qparmas, myinput3: "myinput3" },
        success: function (res) {
            res = JSON.parse(res);
            console.log(res);
            for (i = 0; i < res.length; i++) {
                arr.push(
                    res[i].first_name +
                        " " +
                        res[i].last_name +
                        " " +
                        res[i].team +
                        "/" +
                        res[i].position
                );
                arr1.push(
                    res[i].first_name +
                        "/" +
                        res[i].last_name +
                        "/" +
                        res[i].id +
                        "/" +
                        res[i].team +
                        "/" +
                        res[i].position
                );
            }
        },
    });

    var a,
        b,
        i,
        val = this.value;
    /*close any already open lists of autocompleted values*/
    closeAllLists();
    if (!val) {
        return false;
    }
    currentFocus = -1;
    /*create a DIV element that will contain the items (values):*/
    a = document.createElement("DIV");
    a.setAttribute("id", this.id + "autocomplete-list");
    a.setAttribute("class", "autocomplete-items");
    /*append the DIV element as a child of the autocomplete container:*/
    console.log(arr.length);
    this.parentNode.appendChild(a);
    /*for each item in the array...*/
    for (i = 0; i <= arr.length; i++) {
        //make first letter capital
        console.log(arr[i]);
        //myarr=new Array();
        //myarr[i]=arr[i];
        // if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase() ||  myarr[i].toUpperCase().indexOf(val.toUpperCase()) > -1) {
        /*create a DIV element for each matching element:*/
        b = document.createElement("DIV");
        /*make the matching letters bold:*/
        b.innerHTML += "<strong>" + arr[i] + "</strong>";
        //b.innerHTML += arr[i];

        /*insert a input field that will hold the current array item's value:*/
        var myArr = arr1[i].split("/");
        b.innerHTML +=
            "<input type='hidden'  value='" +
            arr[i] +
            "' data-first_name='" +
            myArr[0] +
            "' data-last_name='" +
            myArr[1] +
            "' data-id='" +
            myArr[2] +
            "' data-team='" +
            myArr[3] +
            "' data-pos='" +
            myArr[4] +
            "'>";
        /*execute a function when someone clicks on the item value (DIV element):*/
        b.addEventListener("click", function (e) {
            /*insert the value for the autocomplete text field:*/
            inp.value = this.getElementsByTagName("input")[0].value;
            first_name =
                this.getElementsByTagName("input")[0].getAttribute(
                    "data-first_name"
                );
            last_name =
                this.getElementsByTagName("input")[0].getAttribute(
                    "data-last_name"
                );
            playerid =
                this.getElementsByTagName("input")[0].getAttribute("data-id");
            playerteam =
                this.getElementsByTagName("input")[0].getAttribute("data-team");
            playerpos =
                this.getElementsByTagName("input")[0].getAttribute("data-pos");
            $("#myInput4").attr("value", inp.value);
            $("#myInput4").attr("data-first_name", first_name);
            $("#myInput4").attr("data-last_name", last_name);
            $("#myInput4").attr("data-id", playerid);
            $("#myInput4").attr("data-team", playerteam);
            $("#myInput4").attr("data-pos", playerpos);

            $("#myInput").attr("value", inp.value);
            $("#myInput").attr("data-first_name", first_name);
            $("#myInput").attr("data-last_name", last_name);
            $("#myInput").attr("data-id", playerid);
            $("#myInput").attr("data-team", playerteam);
            $("#myInput").attr("data-pos", playerpos);
            /*close the list of autocompleted values,
          (or any other open lists of autocompleted values:*/
            closeAllLists();
        });
        a.appendChild(b);
        // }
    }
});
/*execute a function presses a key on the keyboard:*/
//inp.addEventListener("keydown", function (e) {
$("#myInput4").on("keydown", function (e) {
    var x = document.getElementById(this.id + "autocomplete-list");
    if (x) x = x.getElementsByTagName("div");
    if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
      increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
    } else if (e.keyCode == 38) {
        //up
        /*If the arrow UP key is pressed,
      decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
    } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
            /*and simulate a click on the "active" item:*/
            if (x) x[currentFocus].click();
        }
    }
});

function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = x.length - 1;
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
}
function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("autocomplete-active");
    }
}
function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
        if (elmnt != x[i] && elmnt != inp) {
            x[i].parentNode.removeChild(x[i]);
        }
    }
}
/*execute a function when someone clicks in the document:*/
document.addEventListener("click", function (e) {
    closeAllLists(e.target);
});
//}

/*An array containing all the country names in the world:*/
// var leagid = $("input[name='league_id']").val();
// var countries = [];
// var newcountries = [];
// $.ajax({
//   url: '/league/team',
//   method: 'get',
//   data: { id: leagid },
//   success: function (res) {
//     res = JSON.parse(res);
//     for (i = 0; i < res.length; i++) {
//       countries.push(res[i].first_name + ' ' + res[i].last_name + ' ' + res[i].team + '/' + res[i].position)
//       newcountries.push(res[i].first_name + '/' + res[i].last_name + '/' + res[i].id + '/' + res[i].team + '/' + res[i].position)
//     }
//   }
// })
//var countries = ["Afghanistan", "Albania", "Algeria"];

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
// autocomplete(document.getElementById("myInput"), countries, newcountries);
// autocomplete(document.getElementById("myInput2"), countries, newcountries);

$(".updatekeeperlistbutton").on("click", function () {
    if ($("#editkeeperlistround").val() == "") {
        alert("plese enter round number");
        return false;
    }
    val = $("#myInput4").attr("data-id");
    if (!val) {
        val = $("#oldplayerid").val();
    }
    teamid = $("#editkeeperlistteamid").val();
    round_number = $("#editkeeperlistround").val();
    oldroundunber = $("#oldroundunber").val();
    oldplayerid = $("#oldplayerid").val();
    if (val != "") {
        $.ajax({
            url: "/league/" + leagueId + "/updatekeeperlist",
            method: "get",
            data: {
                id: val,
                teamid: teamid,
                round_number: round_number,
                oldroundunber: oldroundunber,
                oldplayerid: oldplayerid,
            },
            success: function (res) {
                if (res.status == "exist") {
                    alert("Record Already Exists");
                } else if (res.status == "success") {
                    document.getElementById("playerBeep").play();
                    window.location =
                        "/league/" +
                        $("input[name='league_id']").val() +
                        "/draft?type=keeperlist";
                } else if (res.status == "error") {
                    alert(res.message);
                }
            },
        });
    }
});
