var pgURL = window.location.href;
pgURL = pgURL.replace("http://web-app.usc.edu/soc/","");
var termNumber = pgURL.substr(0,5);
var departmentName = pgURL.substr(6);


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
		
});

function addClass(){
		var sectionNumber = $(this).children('.sectionNumber').val();
		var courseIndex = $(this).children('.courseIndex').val();
		var sectionIndex = $(this).children('.sectionIndex').val();
		if(checkLogIn() != "1"){
				$.fancybox({
						"content" : "<div>You must be logged in</div>"
				});
		}
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