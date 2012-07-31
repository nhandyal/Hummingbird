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

// add global data to page
var pagedata = "<input type='hidden' id='hb-term' value='"+termNumber+"'/>";
pagedata += "<input type='hidden' id='hb-dept' value='"+departmentName+"'/>";
$("body").append(pagedata);

// add addable icons to full classes
$.get("http://web-app.usc.edu/ws/soc/api/classes/"+departmentName+"/"+termNumber,{},function(response){
		var jsonResponse = JSON.parse(response);
		for (var courseIndex = 0; courseIndex < (jsonResponse.OfferedCourses.course).length; courseIndex++){
				var sectionDataLength = jsonResponse.OfferedCourses.course[courseIndex].CourseData.SectionData.length;
				if(sectionDataLength == undefined){
						// only one offered section
						var sectionData = jsonResponse.OfferedCourses.course[courseIndex].CourseData.SectionData;
						var id = sectionData.id;
						var type = sectionData.type;
						var time = sectionData.start_time+"-"+sectionData.end_time;
						var days = sectionData.day;
						var instructor = sectionData.instructor.last_name;
						if(sectionData.number_registered >= sectionData.spaces_available){
								var iconHTML = "<div class='plus-icon-hummingbird'>";
								iconHTML += "<input type='hidden' class='sectionNumber' value='"+id+"' />";
								iconHTML += "<input type='hidden' class='courseIndex' value='"+courseIndex+"'/>";
								iconHTML += "<input type='hidden' class='sectionIndex' value='-1'/>";
								iconHTML += "<input type='hidden' class='type' value='"+type+"'/>";
								iconHTML += "<input type='hidden' class='time' value='"+time+"'/>";
								iconHTML += "<input type='hidden' class='days' value='"+days+"'/>";
								iconHTML += "<input type='hidden' class='instructor' value='"+instructor+"'/></div>";
								$("#"+id+" > .section").append(iconHTML);
						}
				}
				else{
						// multiple offered sections
						for (var sectionIndex = 0; sectionIndex < sectionDataLength; sectionIndex++){
								var sectionData = jsonResponse.OfferedCourses.course[courseIndex].CourseData.SectionData[sectionIndex];
								var id = sectionData.id;
								var type = sectionData.type;
								var time = sectionData.start_time+"-"+sectionData.end_time;
								var days = sectionData.day;
								var instructor = sectionData.instructor.last_name;
								if(sectionData.number_registered >= sectionData.spaces_available){
										var iconHTML = "<div class='plus-icon-hummingbird'>";
										iconHTML += "<input type='hidden' class='sectionNumber' value='"+id+"' />";
										iconHTML += "<input type='hidden' class='courseIndex' value='"+courseIndex+"'/>";
										iconHTML += "<input type='hidden' class='sectionIndex' value='"+sectionIndex+"'/>";
										iconHTML += "<input type='hidden' class='type' value='"+type+"'/>";
										iconHTML += "<input type='hidden' class='time' value='"+time+"'/>";
										iconHTML += "<input type='hidden' class='days' value='"+days+"'/>";
										iconHTML += "<input type='hidden' class='instructor' value='"+instructor+"'/></div>";
										$("#"+id+" > .section").append(iconHTML);
								}
						}
				}
		}
		var localPath = chrome.extension.getURL("images/plus-icon.png");
		localPath = "url('"+localPath+"')";
		$('.plus-icon-hummingbird').css({"background": localPath});
		
		// add click event listener to plus-icon-hummingbird class and add class
		$('.plus-icon-hummingbird').click(function(){uiLoginStatus(this);});
		
		// show logout option if user is logged in
		if(checkLogIn()=="1"){
				showLogout();
		}
});

function uiLoginStatus(callingOBJ){
		var sectionNumber = $(callingOBJ).children('.sectionNumber').val();
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
												addClass(sectionNumber);
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
				addClass(sectionNumber);
		}
}

function addClass(sectionNumber){
		var courseIndex = $("#"+sectionNumber+" .section .plus-icon-hummingbird .courseIndex").val();
		var sectionIndex = $("#"+sectionNumber+" .section .plus-icon-hummingbird .sectionIndex").val();
		var type = $("#"+sectionNumber+" .section .plus-icon-hummingbird .type").val();
		var time = $("#"+sectionNumber+" .section .plus-icon-hummingbird .time").val();
		var days = $("#"+sectionNumber+" .section .plus-icon-hummingbird .days").val();
		var instructor = $("#"+sectionNumber+" .section .plus-icon-hummingbird .instructor").val();
		var fancyBoxHTML = "<table id='fancybox-addCourse-table'>";
		fancyBoxHTML += "<tr><td colspan='5' id='fancybox-table-description'>You sure bro?</td></tr>";
		fancyBoxHTML += "<tr id='fancybox-table-header'>";
		fancyBoxHTML += "<td>Section</td>";
		fancyBoxHTML += "<td>Type</td>";
		fancyBoxHTML += "<td>Time</td>";
		fancyBoxHTML += "<td>Days</td>";
		fancyBoxHTML += "<td>Instructor</td>";
		fancyBoxHTML += "</tr><tr>";
		fancyBoxHTML += "<td>"+sectionNumber+"</td>";
		fancyBoxHTML += "<td>"+type+"</td>";
		fancyBoxHTML += "<td>"+time+"</td>";
		fancyBoxHTML += "<td>"+days+"</td>";
		fancyBoxHTML += "<td>"+instructor+"</td>";
		fancyBoxHTML += "</tr><tr>";
		fancyBoxHTML += "<td colspan='5' id='fancybox-button-container'><div id='fancybox-button' class='unselectable'>ADD CLASS";
		fancyBoxHTML += "<input type='hidden' id='fancybox-courseIndex' value='"+courseIndex+"'/>";
		fancyBoxHTML += "<input type='hidden' id='fancybox-sectionIndex' value='"+sectionIndex+"'/></div></td>";
		fancyBoxHTML += "</tr></table>";
		
		$.fancybox({
				'content'					: fancyBoxHTML,
				'overlayOpacity'	: 0.7
		});
		
		var localPath = chrome.extension.getURL("fancybox/fancy_close.png");
		var background = "transparent url('"+localPath+"')";
		$('#fancybox-close').css({"background":background});
		
		// add add class button event listener
		$("#fancybox-button-container").click(function(){addCourse()});
}

function addCourse(){
		var localPath = chrome.extension.getURL("images/loading.gif");
		var fancyBoxHTML = "<div style='width:400px'><div style='width:50px; margin:auto'><img src='"+localPath+"' width='50' height='50' style='opacity:0.3'/></div></div>";
		$.fancybox({
				'content'					: fancyBoxHTML,
				'overlayOpacity'	: 0.7
		});
		$.get('http://www.hummingbirdapplication.com/application/extension/ex-addClassV2.php',{
						'courseIndex'		: $('#fancybox-courseIndex').val(),
						'sectionIndex'	: $('#fancybox-sectionIndex').val(),
						'term'					: $('#hb-term').val(),
						'dept'					: $('#hb-dept').val()
				},
				function(response){
						var jsonResponse = JSON.parse(response);
						var fancyBoxHTML = "<div style='font-size:14px; text-align:center; width:400px'>"+jsonResponse.message+"</div>";
						$.fancybox({
								'content'					: fancyBoxHTML,
								'overlayOpacity'	: 0.7
						});
				}
		);
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
