"use strict";

$('.create_league form .select_view .select_draft.league_size ul li input').on('click', function () {
	var counts = $(this).val();
	$('.select_draft.draft_round label').text(counts);
});

$('.city_charts .top_draft ul li.player img').on('click', function () {
	$('.city_charts .top_draft ul li .player_draft').toggleClass('player_on');
});



