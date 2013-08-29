$(document).ready(function(){
		
		// initialize auto tab on phone number field
		$('#areacode, #phoneNumber1, #phoneNumber2').autotab_magic().autotab_filter('numeric');
		
		
		$('#settings-toggle').click(function(){
				if($('.settings-visible').length == 0){
						
						// Set the location of the settings box
						var stPos = $('#settings-toggle').position();
						var cssOBJ = {
								'top'		: stPos.top+30,
								'left'	: stPos.left-161
						};
						$('#settings').css(cssOBJ);
						
						$('#settings').css("display","inline-block");
						$('#settings-toggle').addClass("settings-visible");
				}
				else{
						$('#settings').css("display","none");
						$('#settings-toggle').removeClass("settings-visible");
				}
		});
		
		$('.phone-notification').click(function(){
				if($('.phone-notifications-visible').length == 0){
						// Set the location of the phone notifications box
						var pnPos = $('.phone-notification').position();
						var cssOBJ = {
								'top'			: pnPos.top,
								'left'		: pnPos.left,
								'display'	: 'inline-block'
						}
						$('#phone-notifications-menu').css(cssOBJ);
						$('.phone-notification').addClass('phone-notifications-visible');
				}
				else{
						$('#phone-notifications-menu').css({'display':'none'});
						$('.phone-notification').removeClass('phone-notifications-visible');
				}
		});
		
		$('.phone-notification-status').click(function(){
				$('.phone-notification').html($(this).text()+"<span class='down-arrow' style='margin-top:9px'>");
				$('#phone-notifications-menu').css({'display':'none'});
				$('.phone-notification').removeClass('phone-notifications-visible');
		});
		
		$('#remove-phone-link').click(function(){
				$.get('removePhone.php',{},function(response){
						window.location.reload();
				});
		});
		
		$('#add-phone-link').click(function(){
				$('#no-phone-message').css({'display':'none'});
				$('#add-phone-content').css({'display':'block'}).animate({'height':150},500,function(){});
		});
		
		$('#mobile-carrier-toggle').click(function(){
				if($('.carriers-visible').length == 0){
						// Set the location of the carriers box
						var crPos = $('#mobile-carrier-toggle').position();
						var cssOBJ = {
								'top'			: crPos.top,
								'left'		: crPos.left
						}
						$('#mobile-carriers').css(cssOBJ);
						$('#mobile-carriers').css({'display':'inline-block'}).addClass('carriers-visible');
				}
				else{
						$('#mobile-carriers').css({'display':'none'}).removeClass('carriers-visible');
				}
		});
		
		$('.mobile-carrier').click(function(){
				$('#carrier-selected').html($(this).text());
				$('#mobile-carriers').css({'display':'none'}).removeClass('carriers-visible');
				if($('#areacode').val().length == 3 && $('#phoneNumber1').val().length == 3 && $('#phoneNumber2').val().length == 4){
						$('#verify-mobile').addClass('clickable').removeClass('unclickable');
				}
		});
		
		$('#areacode').blur(function(){
				if($('#areacode').val().length == 3 && $('#phoneNumber1').val().length == 3 && $('#phoneNumber2').val().length == 4 && $('#carrier-selected').text() != "Mobile Carrier"){
						$('#verify-mobile').addClass('clickable').removeClass('unclickable');
				}
		});
		
		$('#phoneNumber1').blur(function(){
				if($('#areacode').val().length == 3 && $('#phoneNumber1').val().length == 3 && $('#phoneNumber2').val().length == 4 && $('#carrier-selected').text() != "Mobile Carrier"){
						$('#verify-mobile').addClass('clickable').removeClass('unclickable');
				}
		});
		
		$('#phoneNumber2').blur(function(){
				if($('#areacode').val().length == 3 && $('#phoneNumber1').val().length == 3 && $('#phoneNumber2').val().length == 4 && $('#carrier-selected').text() != "Mobile Carrier"){
						$('#verify-mobile').addClass('clickable').removeClass('unclickable');
				}
		});
		
		$('#verify-mobile').click(function(){
				if($('.clickable').length != 0){
						$('#add-mobile-container').css({'display':'none'});
						$('#verify-mobile-loading').css({'display':'block'});
						var phone_number = $('#areacode').val()+$('#phoneNumber1').val()+$('#phoneNumber2').val();
						var carrier = $('#carrier-selected').text();
						$.get('sendTextVerification.php',{
										'phone_number'			: phone_number,
										'carrier'						: carrier
								},
								function(response){
										$('#verify-mobile-loading').css({'display':'none'});
										$('#verify-mobile-code').css({"display":'block'}).animate({'height':150},500,function(){});
								}
						);
				}
		});
		
		$('#save-changes-addPhone').click(function(){
				$.get('verifyMobileCode.php',{
								'phone_vrf_code'	: $('#mobile-verification-code').val()
						},
						function(response){
								var jsonResponse = JSON.parse(response);
								if(jsonResponse.status == 0){
										window.location.reload();
								}
								else{
										$('#verify-mobile-error').css({'display':'inline-block'});
								}
						}
				);
		});
		
		$('#save-changes').click(function(){
				var text_notify = ($('.phone-notification').text()).charAt(0)
				$.get('saveChanges.php',{
								'text_notify' : text_notify
						},
						function(response){
								window.location.reload();
						}
				);
		});
		
});


function centerContent(){
		// Center the master wrapper on the screen;
		var masterWrapperMargin = ($(document).height() - 70 -480)/2;
		$('#master-wrapper').css({'margin-top':masterWrapperMargin+"px"});	
}