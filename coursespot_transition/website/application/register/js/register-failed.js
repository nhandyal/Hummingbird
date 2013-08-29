$(document).ready(function(){
		var wHeight = ($(window).height()-($('#page-content').height()))/2;
		$('#page-content').css({"padding-top": wHeight});
		
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
});