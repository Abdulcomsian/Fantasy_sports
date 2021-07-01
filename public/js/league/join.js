$(function(){
	$("#joinLeague").validate({
        ignore: [],  // ignore NOTHING
        rules: {
            'team_id': {
                required: true,
            },
            'team_name': {
                required: true,
                minlength: 3,
                maxlength: 200
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
            if(element.hasClass('select2') && element.next('.select2-container').length) {
                error.insertAfter(element.next('.select2-container'));
            } else if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            }
            else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
                error.insertAfter(element.parent().parent());
            }
            else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                error.appendTo(element.parent().parent());
            }
            else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            //form.submit();
            joinLeague(form);
        },
    });

	$('#teamId').on('change', function(e){
	   $("input[name='team_name']").val($(this).find(':selected').data('team_name'));
	});    
});

function joinLeague(form){
    $.ajax({
        type: 'POST',
        url: '/league/'+$("input[name='league_id']").val()+'/join',
        data: $(form).serialize(),
        success: function (response) {
            if(response.status == 200){
               	//appendSuccessMessage(response.message);
                successMessage(response.message);
                $('html').scrollTop(0);
                setTimeout(function(){ window.location.href = '/home'; }, 1000);
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