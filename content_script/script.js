// create logout nav menu item and add event listener
var hummingBirdLoagout = "<div id='hb-logout'>Logout of Hummingbird</div>";
$('#nav').append(hummingBirdLoagout);
$('#hb-logout').click(function(){
		hbLogout();
});

var pgURL = window.location.href;
pgURL = pgURL.replace("http://web-app.usc.edu/soc/","");
var termNumber = pgURL.substr(0,5);
var departmentName = pgURL.substr(6);


// add addable icons to full classes
$.get("http://web-app.usc.edu/ws/soc/api/classes/"+departmentName+"/"+termNumber,{},function(response){
		var jsonResponse = JSON.parse(response);
		for (var courseIndex = 0; courseIndex < (jsonResponse.OfferedCourses.course).length; courseIndex++){
				var sectionDataLength = jsonResponse.OfferedCourses.course[courseIndex].CourseData.SectionData.length;
				if(sectionDataLength == undefined){
						// only one offered section
						var sectionData = jsonResponse.OfferedCourses.course[courseIndex].CourseData.SectionData;
						var id = sectionData.id;
						if(sectionData.number_registered >= sectionData.spaces_available){
								var iconHTML = "<div class='plus-icon-hummingbird'>";
								iconHTML += "<input type='hidden' class='sectionNumber' value='"+id+"' />";
								iconHTML += "<input type='hidden' class='courseIndex' value='"+courseIndex+"'/>";
								iconHTML += "<input type='hidden' class='sectionIndex' value='-1'/></div>";
								$("#"+id+" > .section").append(iconHTML);
						}
				}
				else{
						// multiple offered sections
						for (var sectionIndex = 0; sectionIndex < sectionDataLength; sectionIndex++){
								var sectionData = jsonResponse.OfferedCourses.course[courseIndex].CourseData.SectionData[sectionIndex];
								var id = sectionData.id;
								if(sectionData.number_registered >= sectionData.spaces_available){
										var iconHTML = "<div class='plus-icon-hummingbird'>";
										iconHTML += "<input type='hidden' class='term' value='"+termNumber+"'/>";
										iconHTML += "<input type='hidden' class='dept' value='"+departmentName+"'/>";
										iconHTML += "<input type='hidden' class='sectionNumber' value='"+id+"' />";
										iconHTML += "<input type='hidden' class='courseIndex' value='"+courseIndex+"'/>";
										iconHTML += "<input type='hidden' class='sectionIndex' value='"+sectionIndex+"'/></div>";
										$("#"+id+" > .section").append(iconHTML);
								}
						}
				}
		}
		var localPath = chrome.extension.getURL("images/plus-icon.png");
		localPath = "url('"+localPath+"')";
		$('.plus-icon-hummingbird').css({"background": localPath});
		
		// add click event listener to plus-icon-hummingbird class and add class
		$('.plus-icon-hummingbird').click(function(){addClass();});
		
		// show logout option if user is logged in
		if(checkLogIn()=="1"){
				showLogout();
		}
});

function addClass(){
		var term = $(this).children('.termNumber').val();
		var dept = $(this).children('.dept').val();
		var sectionNumber = $(this).children('.sectionNumber').val();
		var courseIndex = $(this).children('.courseIndex').val();
		var sectionIndex = $(this).children('.sectionIndex').val();
		if(checkLogIn() != "1"){
				var loginHTML = "<div id='page-content'><p id='error-message'>Login to Hummingbird</p><div id='login-wrapper'><form id='login' method='post' autocomplete='off'><div class='login-subContainer'>";
				loginHTML += "<input type='email' class='textfields' id='login-email' name='email' placeholder='e-mail' required='required'/><input type='password' class='textfields' id='login-pwd' name='pwd' placeholder='password' required='required'/><input type='submit' id='login-submit' value='login'/></div>";
				loginHTML	+= "<div class='login-subContainer'><input id='login-persistence' type='checkbox' name='persistence' value='1'/><span id='login-label'>Remember me</span><a id='login-fPassword'>Forgot your password?</a></div></form></div></div>";
				$.fancybox({
						"content" : loginHTML
				});
				var localPath = chrome.extension.getURL("fancybox/fancy_close.png");
				var background = "transparent url('"+localPath+"')";
				$('#fancybox-close').css({"background":background});
				
				// add fnacybox event listeners
				$('#login-fPassword').click(function(){
						fgpwd();
				});
				$('#login').submit(function(e){
						$.ajax({
								type: "POST",
								async: false,
								url: "http://www.hummingbirdapplication.com/application/extension/ex-login.php",
								data: {
										email 			: $('#login-email').val(),
										pwd					: $('#login-pwd').val(),
										persistence :	$('#login-persistence').val()
								},
								success: function(response){
										var jsonResponse = JSON.parse(response);
										if(jsonResponse.status == 0){
												showLogout();
										}
										else{
												$('#error-message').text(jsonResponse.message);
										}
								}
						});
						e.preventDefault();
						return false;
				});	
		}
		else{
				console.log("user already logged in");
		}
}

function fgpwd(){
		chrome.extension.sendRequest("fgpwd");
}

function checkLogIn(){
		var loggedIn;
		$.ajax({
						type: "GET",
						async: false,
						url: "http://www.hummingbirdapplication.com/application/extension/ex-loggedIn.php",
						success: function(response){
								loggedIn = response;
						}
				}
		);
		return loggedIn;
}

// hide logout in nav bar
function hideLogout(){
		$("#hb-logout").css({"display":"none"});
}
// show logout in nav bar
function showLogout(){
		$("#hb-logout").css({"display":"inline-block"});
}

function hbLogout(){
		$.get("http://www.hummingbirdapplication.com/application/extension/ex-logout.php",{},function(response){
				if (response == "1")
						hideLogout();
		});
}
