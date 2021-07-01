var remianingTimer = '';
var timerInterval = '';

if(laterDateTime != ''){
	laterDateTime = moment(laterDateTime).format('DD/MM/YYYY HH:mm:ss');
	countDownTimer();
}

$(function(){

	$("#timerForm").validate({
        ignore: [],  // ignore NOTHING
        rules: {
            'hours': {
                required: true,
                min: 0,
                max: 99
            },
            'minutes': {
                required: true,
                min: 0,
                max: 59
            },
            'seconds': {
                required: true,
                min: 0,
                max: 59
            }
        },
        messages: {
	      	minutes: {
	      		max: "Max 59 minutes are allowed.",
	    	},      
	     	seconds: {
	      		max: "Max 59 seconds are allowed.",
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
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            saveTimer();
        },
    });

    $('.city_charts .timer_box ul li .time .btn_view span .clock .fa-clock-o').on('click', function(){
	    $('#timerForm').validate().resetForm();
	    $('#timerForm').trigger("reset");
	    $('.city_charts .timer_box ul li .time .btn_view span .clock .time_duration').toggleClass('time_on');
	});

	$('.timer').on('keydown', function(e){
	    if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) { 
	      	if(this.value.length==2) return false;
	    }
	});

	$('#timerBtn').on('click', function(e){
		timerSettings($(this), $(this).attr('data-type'));
	});

	$('#refreshTime').on('click', function(e){
		timerSettings($('#timerBtn'), 'refresh');
	});
});

function saveTimer(){
	let hours = parseInt($("input[name='hours']").val()) || 0, minutes = parseInt($("input[name='minutes']").val()) || 0, seconds = parseInt($("input[name='seconds']").val()) || 0;
	if(hours == 0 && minutes == 0 && seconds == 0){
		errorMessage('Try again.');
	}else{
		hours = hours < 10 ? '0'+hours : hours;
		minutes = minutes < 10 ? '0'+minutes : minutes;
		seconds = seconds < 10 ? '0'+seconds : seconds;
		time = hours+':'+minutes+':'+seconds;
		$.ajax({
	        type: 'POST',
	        url: '/league/'+leagueId+'/draft/timer/save',
	        data: {timer_value : time},
	        success: function (response) {
	            if(response.status == 200){
	                clearInterval(timerInterval);
	                successMessage(response.message);
	                displayTime(response.data.timer_value);
	                $('#currentDuration').text(response.data.timer_value);
	                $('.city_charts .timer_box ul li .time .btn_view span .clock .time_duration').toggleClass('time_on');
	            }else{
	                errorMessage(response.message);
	            }
	        },
	    });
	}
}

function timerSettings(self, type){
	if(type == 'stop' || type == 'refresh'){
		clearInterval(timerInterval);
	}
	$.ajax({
        type: 'POST',
        url: '/league/'+leagueId+'/draft/timer/'+type,
        data: {remaining_timer : remianingTimer, local_date_time : moment().format('MM/DD/YYYY HH:mm:ss')},
        success: function (response) {
            if(response.status == 200){
                if(type == 'start'){
                	if(response.data.timer_type == 'restart' || response.data.timer_type == 'start'){
                		durationTime = response.data.timer_value; 
                		laterDateTime = '';
					}
                	countDownTimer();
                	self.attr('data-type', 'stop');
                	self.html('<i class="fa fa-pause"></i>');
                }else if(type == 'refresh'){
                	self.attr('data-type', 'start');
                	self.html('<i class="fa fa-play"></i>');
                	durationTime = remianingTimer = response.data.timer_value;
                	displayTime(durationTime);
                }else{
                	self.attr('data-type', 'start');
                	self.html('<i class="fa fa-play"></i>');
                	durationTime = remianingTimer;
                }
            }else{
                errorMessage(response.message);
            }
        },
    });
}

function countDownTimer(){
	//Duration time is defined on the draft page
	duration = durationTime.split(':');
	if(laterDateTime == ''){
		laterDateTime = moment().add(duration[0], 'h').add(duration[1], 'm').add(duration[2], 's').format('DD/MM/YYYY HH:mm:ss');	
	}
	
	// Update the count down every 1 second
	timerInterval = setInterval(function() {         

		const earlierDateTime = moment().format('DD/MM/YYYY HH:mm:ss');

		const ms = moment(laterDateTime, "DD/MM/YYYY HH:mm:ss").diff(moment(earlierDateTime, "DD/MM/YYYY HH:mm:ss"));
		const d = moment.duration(ms);
		let hours = `${Math.floor(d.asHours())}`;
		let diff = '';
		if(hours == 0){
			hours = '00';
		  	diff = `${moment.utc(ms).format("mm:ss")}`;
		}else{
		  	hours = hours < 10 ? 0+hours : hours; 
		  	diff = hours+`${moment.utc(ms).format(":mm")}`;  
		}
		remianingTimer = hours+`${moment.utc(ms).format(":mm:ss")}`;
		$('#countDownTimer').text(diff);
		var lastSeconds = ["00:00:00", "00:00:01", "00:00:02", "00:00:03", "00:00:04", "00:00:05"];
		if(lastSeconds.includes(remianingTimer)){
			document.getElementById( 'timerBeep' ).play();
		}
		
		// If the count down is over, write some text 
		if (remianingTimer == '00:00:00') {
		  	clearInterval(timerInterval);
		  	timerSettings($('#timerBtn'), 'stop');
		}
	}, 1000);
};

function displayTime(durationTime){
	let textTime = durationTime.split(':');
	if(textTime[0] == '00'){
		textTime = textTime[1]+':'+textTime[2];
	}else{
		textTime = textTime[0]+':'+textTime[1];
	}
	$('#countDownTimer').text(textTime);
}