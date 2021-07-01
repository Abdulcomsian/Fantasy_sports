$(function(){
	$("#checkLeagueExist").validate({
        ignore: [],  // ignore NOTHING
        rules: {
            'joiner_key': {
                required: true
            }
        },
        highlight: function(element) {
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function(element) {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        },
        errorClass: 'help-block',
        errorPlacement: function (error, element) {
            /*if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            }
            else {
                error.insertAfter(element);
            }*/
        },
        submitHandler: function(form) {
            checkLeagueExist(form);
        },
    });

	$('#teamId').on('change', function(e){
	   $("input[name='team_name']").val($(this).find(':selected').data('team_name'));
	});    
});

function checkLeagueExist(form){
    $.ajax({
        type: 'POST',
        url: '/league/join/key',
        data: $(form).serialize(),
        success: function (response) {
            if(response.status == 200){
                let league = response.data.league;
                if(league && league.users[0] && league.users[0].id){
                    //appendSuccessMessage('You are already a part of this league. Click <a href="/league/'+league.id+'/settings">here</a> to check settings.');
                    successMessage('You are already a part of this league. Click <a href="/league/'+league.id+'/settings">here</a> to check settings.');
                    $('html').scrollTop(0);
                }else{
                    setTimeout(function(){ window.location.href = '/league/join?key='+league.joiner_key; }, 1000);
                }
            }else{
                errorMessage(response.message);
            }
        },
    });
}

function appendSuccessMessage(message){
	$('.successMessage').append('<div class="alert alert-success alert-dismissible" role="alert">'+
					            '<span class="message">'+message+'</span>'+
					            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
					                '<span aria-hidden="true">&times;</span>'+
					            '</button>'+
					        '</div>');
}