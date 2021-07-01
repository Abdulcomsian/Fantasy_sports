$(function(){
    $("#createLeague").validate({
        ignore: [],  // ignore NOTHING
        rules: {
            'name': {
                required: true,
                minlength: 3,
                maxlength: 200
            },
            'draft_type': {
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
            createLeague(form);
        },
    });

    function createLeague(form){
        $.ajax({
            type: 'POST',
            url: '/league',
            data: $(form).serialize(),
            success: function (response) {
                if(response.status == 200){
                    successMessage(response.message);
                    $('html').scrollTop(0);
                    setTimeout(function(){ window.location.href = '/league/'+response.data.id+'/order'; }, 1000);
                }else{
                    errorMessage(response.message);
                }
            },
        });
    }
});