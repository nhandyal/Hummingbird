$('document').ready(function(){
		$.browser.chrome = $.browser.webkit && !!window.chrome;
		$.browser.safari = $.browser.webkit && !window.chrome;

		// check the browser type
		if(!($.browser.chrome == true || $.browser.mozilla == true)){
				var htmlContent = "<div style='width:300px; text-align:center; color:rgb(205,20,20)'>This site is best viewed in Google Chrome 20.0+ or Mozilla Firefox 13.0+.</div>"
				$.fancybox({
						'overlayOpacity'		: 0.7,
						'content'						: htmlContent
				});
				
		}
		
		//initializes the page layout. Sets margins and padding based on users screen size
		initializePage();
		
		
		// collapse facebook like box when ui-controls-fbCollapse is clicked
		$("#ui-controls-fbCollapse").click(function(){
				$("#fb-like").animate({"height":0},450,function(){});
		});
		
		// Shows the login div when a user mouse overs the login container
		$('#logo-container').mouseover(function(){
				$('#login-wrapper').animate({"opacity":1},700,function(){});
		});
		
		// scroll the page when a user clicks on a navigation element
		$('.ui-menu-scrollable').click(function(){
				var targetElement = "";
				switch($(this).text()){
						case "About":
								targetElement = "#about-container";
								break;
						case "Register":
								targetElement = "#registration-master-container";
								break;
						case "Contact":
								targetElement = "#contact-container";
								break;
				}
				var targetPosition = ($(targetElement).position().top)-20;
				$('html, body').animate({ scrollTop: targetPosition},650,function(){});
				return false;
		});
		
		// show the login box 
		$('#login-link').click(function(){
				$('#login-link').css({'display':'none'})
				$('#login-wrapper').css({'display':'block'});
				return false;
		});
		
		
		// How it works canvas controls next
		$('#next').click(function(){
				var nextId = '#';
				var currentId = $('#works-content-wrapper .current').attr('id');
				if(currentId == 4)
						nextId += 0;
				else
						nextId += (parseInt(currentId,10) + 1);
				
				currentId = '#'+parseInt(currentId,10);
				
				changeCanvas(currentId,nextId);
				
		});
		
		
		// How it works canvas controls prev
		$('#prev').click(function(){
				var nextId = '#';
				var currentId = $('#works-content-wrapper .current').attr('id');
				if(currentId == 0)
						nextId += 4;
				else
						nextId += (parseInt(currentId,10) - 1);
				
				currentId = '#'+parseInt(currentId,10);
				
				changeCanvas(currentId,nextId);
		});
		
		
		// Show password error if a user leaves it blank
		$('#pwd').blur(function(){
				if(($('#pwd').val()).length < 6)
						$('#pwd-error').css({"opacity":1});
				else
						$('#pwd-error').css({"opacity":0});
		});
		
		
		// Show conf pwd error if passwords don't match
		$('#confirm-pwd').blur(function(){
				var cpwd = $('#confirm-pwd').val();
				if(cpwd != $('#pwd').val())
						$('#confirm-pwd-error').css({"opacity":1});
				else
						$('#confirm-pwd-error').css({"opacity":0});
		});
		
		// verify password field is filled and password and conf password match and password length is at least 6 characters long
		$('#registration-form').submit(function(){
				if($('#confirm-pwd').val() == $('#pwd').val() && $('#pwd').val() != "" && ($('#pwd').val()).length >= 6)
						return true;
				else if($('#confirm-pwd').val() != $('#pwd').val() && $('#pwd').val() != "" ){
						$('#confirm-pwd-error').css({"opacity":1});
				}
				return false;
		});
		
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
										$('#form-container').fadeOut(700,function(){
												$('#form-container').replaceWith(jsonResponse.message);
												$('#serv-response').animate({"opacity":1},500,function(){});
										});
								}
						}
				);
				return false;
		});
		
}); // ---------------------------------------------------------------------------------End of document.ready 


// Brings in the next or prev canvas element for how it works
function changeCanvas(currentId, nextId){
		$(currentId).fadeOut(500,function(){
				$(currentId).removeClass('current');
				$(nextId).fadeIn(700,function(){
						$(nextId).addClass('current');
				});
		});
}


// initializes page elements based on users current screen resolution
function initializePage(){
				
				var padding = ($(window).height()-150)/2;
				var mainContainerVerticalOffset = padding;
		var logoContainerHeight = ($(window).height()/2);
		
		$('#main-content-container').css({"padding-top": mainContainerVerticalOffset});
		$('#logo-container').css({"padding-bottom": padding});
		$('#works-content-wrapper .current').fadeIn(200);
}