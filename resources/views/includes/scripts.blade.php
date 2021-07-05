<!-- Jquery -->
<script src="{{ asset('plugins/jquery/jquery-3.2.1.min.js') }}"></script>
<!-- Bootstrap JS-->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- Jquery Toast Message JS -->
<script src="{{ asset('plugins/toaster/jquery.toast.min.js') }}"></script>
<!-- App JS -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('plugins/sweet-alert/sweetalert.min.js') }}"></script>
<script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
<script type="text/javascript">
	$(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });   

		$(document).ajaxSend(function(e, xhr, opt){
			let url = opt.url.split('/');
			if(!(url[4] && url[4] == 'timer')){
				$('.ajax-loader').css("visibility", "visible");	
			}
		});
		$(document).ajaxComplete(function(){
		    $('.ajax-loader').css("visibility", "hidden");
		});
    });

    function successMessage(message) {
    	$.toast({
		    heading: 'Success',
		    text: message,
		    showHideTransition: 'slide',
		    icon: 'success',
		    position: 'top-right'
		})
    }

    function errorMessage(message) {
    	$.toast({
		    heading: 'Error',
		    text: message,
		    showHideTransition: 'fade',
		    icon: 'error',
		    position: 'top-right',
		})
    }
	jQuery(".openBtn").click(function(){
		jQuery("#side-nav").css("display","block")
	})
	jQuery(".side-nav-content button").click(function(){
		jQuery("#side-nav").css("display","none")
	})
</script>