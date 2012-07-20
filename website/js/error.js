$(document).ready(function(){
		var wHeight = (($(window).height()-250-($('#page-content').height()))/2)
		$('#page-content').css({"padding-top": wHeight});
		
		$('#contact-form').submit(function(){
				$.post("contact-support.php",{
								'type'			: $('#type').val(),
								'name'			:	$('#form-name').val(),
								'email'			: $('#form-email').val(),
								'message'		: $('#form-textarea').val()
						},
						function(response){
								var jsonResponse = JSON.parse(response);
								if(jsonResponse.status == 0){
										$('#contact-form').fadeOut(700,function(){
												$('#contact-container').replaceWith(jsonResponse.message);
										});
								}
						}
				);
				return false;
		});
});