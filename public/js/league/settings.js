$(function () {
    $("#updateLeague").validate({
        ignore: [], // ignore NOTHING
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 200,
            },
            league_size: {
                required: true,
            },
            draft_round: {
                required: true,
            },
        },
        highlight: function (element) {
            $(element)
                .closest(".form-group")
                .removeClass("has-success")
                .addClass("has-error");
        },
        success: function (element) {
            $(element)
                .closest(".form-group")
                .removeClass("has-error")
                .addClass("has-success");
        },
        errorClass: "help-block",
        errorPlacement: function (error, element) {
            if (
                element.hasClass("select2") &&
                element.next(".select2-container").length
            ) {
                error.insertAfter(element.next(".select2-container"));
            } else if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else if (
                element.prop("type") === "radio" &&
                element.parent(".radio-inline").length
            ) {
                error.insertAfter(element.parent().parent());
            } else if (
                element.prop("type") === "checkbox" ||
                element.prop("type") === "radio"
            ) {
                error.appendTo(element.parent().parent());
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            //form.submit();
            updateLeague(form);
        },
    });

    $(".teams").on("click", ".deleteTeam", function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this league!",
            buttons: true,
        }).then((willDelete) => {
            if (willDelete) {
                deleteTeam($(this));
            }
        });
    });

    $(".addTeam").on("click", function () {
        let teamNumber = $(".teams tbody tr").length + 1;
        $(".teams tbody").append(
            "<tr>" +
                '<td class="txt_head teamId" data-id="">' +
                teamNumber +
                "</td>" +
                '<td class="teamName" contenteditable="true">Team Name</td>' +
                '<td class="teamEmail" contenteditable="true">' +
                $("input[name='user_email']").val() +
                "</td>" +
                '<td class="deleteTeam"><i class="fa fa-trash" aria-hidden="true"></i></td>' +
                "</tr>"
        );
    });

    $(".addCommish").on("click", function (e) {
        e.preventDefault();
        if ($('select[name="commish_user_id"]').val()) {
            saveCommish($('select[name="commish_user_id"]').val(), 1);
            //$('select[name="commish_user_id"]').val('');
            location.reload();
        }
    });

    $(".addCoCommish").on("click", function (e) {
        e.preventDefault();
        if ($('select[name="co_commish_user_id"]').val()) {
            saveCommish($('select[name="co_commish_user_id"]').val(), 2);
            //$('select[name="co_commish_user_id"]').val('');
            location.reload();
        }
    });

    $(".lequeMode").on("change", function () {
        var leagueStatus = $(this).val();
        let modeId = leagueStatus == "keeper" ? "keeperMode" : "draftMode";
        if (this.checked) {
            swal({
                title: "Are you sure that you want to advance the league?",
                text: "It is recommended that you double check that you have the correct number of teams before advancing the league.",
                buttons: true,
            }).then((confirm) => {
                if (confirm) {
                    changeLeagueStatus(leagueStatus);
                    let otherModeId =
                        modeId == "draftMode" ? "keeperMode" : "draftMode";
                    $("#" + modeId).prop("checked", true);
                    $("#" + otherModeId).prop("checked", false);
                } else {
                    $("#" + modeId).prop("checked", false);
                }
            });
        } else {
            $("#" + modeId).prop("checked", true);
        }
    });
    $(".lequeMode2").on("change", function () {
        var leagueStatus = $(this).val();
        let modeId = leagueStatus == "keeper" ? "keeperMode" : "draftMode";
        if (this.checked) {
            swal({
                title: "Are you sure that you want to advance the league?",
                text: "It is recommended that you double check that you have the correct number of teams before advancing the league.",
                buttons: true,
            }).then((confirm) => {
                if (confirm) {
                    changeLeagueStatus2(leagueStatus);
                    let otherModeId =
                        modeId == "draftMode" ? "keeperMode" : "draftMode";
                    $("#" + modeId).prop("checked", true);
                    $("#" + otherModeId).prop("checked", false);
                } else {
                    $("#" + modeId).prop("checked", false);
                }
            });
        } else {
            $("#" + modeId).prop("checked", true);
        }
    });
});

function updateLeague(form) {
    let teams = prepareTeamData();
    $.ajax({
        type: "POST",
        url: "/league/" + $("input[name='league_id']").val() + "/settings",
        data: {
            league_id: $("input[name='league_id']").val(),
            name: $("input[name='name']").val(),
            draft_round: $("input[name='draft_round']").val(),
            teams: teams,
        },
        success: function (response) {
            if (response.status == 200) {
                successMessage(response.message);
                $("html").scrollTop(0);
            } else {
                errorMessage(response.message);
            }
        },
    });
}

function prepareTeamData() {
    let teams = [];
    $(".teams tbody tr").each(function () {
        let team = {};
        team.team_id = $(this).find(".teamId").data("id");
        team.team_name = $(this).find(".teamName").text().trim();
        team.team_email = $(this).find(".teamEmail").text().trim();
        team.team_permission = $(this)
            .find(".teamPermission")
            .find("input")
            .val();
        teams.push(team);
    });
    return teams;
}

function saveCommish(userId, type) {
    $.ajax({
        type: "POST",
        url: "/league/" + $("input[name='league_id']").val() + "/commish/save",
        data: { user_id: userId, type: type },
        success: function (response) {
            if (response.status == 200) {
                /*appendSuccessMessage(response.message);*/
                successMessage(response.message);
                $("html").scrollTop(0);
            } else {
                errorMessage(response.message);
            }
        },
    });
}

function deleteTeam(elem) {
    let id = elem.closest("tr").find("td.teamId").data("id");
    if (id && id != "") {
        $.ajax({
            type: "POST",
            url:
                "/league/" +
                $("input[name='league_id']").val() +
                "/teams/delete",
            data: { team_id: id },
            success: function (response) {
                if (response.status == 200) {
                    elem.closest("tr").remove();
                    updatePicksColumn();
                    /*appendSuccessMessage(response.message);*/
                    successMessage(response.message);
                    $("html").scrollTop(0);
                } else {
                    errorMessage(response.message);
                }
            },
        });
    } else {
        elem.closest("tr").remove();
    }
}

function updatePicksColumn() {
    $(".teams tbody tr").each(function (i, elem) {
        $(elem)
            .find(".txt_head")
            .text(i + 1);
    });
}

function appendSuccessMessage(message) {
    $(".successMessage").html("");
    $(".successMessage").append(
        '<div class="alert alert-success alert-dismissible" role="alert">' +
            '<span class="message">' +
            message +
            "</span>" +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span>' +
            "</button>" +
            "</div>"
    );
}

function changeLeagueStatus(status) {
    $.ajax({
        type: "POST",
        url: "/league/" + $("input[name='league_id']").val() + "/changeStatus",
        data: { status: status },
        success: function (response) {
            if (response.status == 200) {
                successMessage(response.message);
                $("html").scrollTop(0);
                setTimeout(function () {
                    window.location.href =
                        "/league/" + response.data.id + "/settings";
                }, 1000);
            } else {
                errorMessage(response.message);
            }
        },
    });
}

function changeLeagueStatus2(status) {
    $.ajax({
        type: "POST",
        url: "/league/" + $("input[name='league_id']").val() + "/changeStatus",
        data: { status: status },
        success: function (response) {
            if (response.status == 200) {
                successMessage(response.message);
                $("html").scrollTop(0);
                setTimeout(function () {
                    window.location.href =
                        "/league/" + response.data.id + "/draft";
                }, 1000);
            } else {
                errorMessage(response.message);
            }
        },
    });
}
