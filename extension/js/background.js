chrome.contextMenus.create({
		"title" : "Monitor with Hummingbird",
		"type" : "normal",
		"contexts" : ["selection"],
		"documentUrlPatterns" : ["http://web-app.usc.edu/soc/*"],
		"onclick" : clickCallBack
});

function clickCallBack(info){
		chrome.browserAction.setIcon({path: "../images/icon19_yellow.png"});
		if(checkLogIn() != "1"){
				chrome.browserAction.setIcon({path: "../images/icon19.png"});
				chrome.tabs.create({url : "http://www.hummingbirdapplication.com/login.php?err=3"});
		}
		else{
				// user is logged in
				var selectionText = info.selectionText;
				if(validSelection(selectionText)){
						selectionText = selectionText.replace("D",""); //Remove the D from the section number
						selectionText = selectionText.replace("R",""); //Remove the R from the section number
						var courseInfo = (info.pageUrl).replace("http://web-app.usc.edu/soc/","");
						var termNumber = courseInfo.substring(0,courseInfo.indexOf("/"));
						var deptName = courseInfo.substring(courseInfo.indexOf("/"));
						var courseURL = "http://web-app.usc.edu/ws/soc/api/classes"+deptName+"/"+termNumber;
						validateSectionNumber(courseURL, selectionText, termNumber);
				}
		}
};

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

function validSelection(selectionText){
		if(selectionText.length == 5 || selectionText.length == 6){
				return true;
		}
		else{
				alert("You made an invalid selection. Pleae select the section you want from the sections collumn.");
				return false;
		}
}

function validateSectionNumber(courseURL, selectionText, termNumber){
		var jsonResponse;
		$.ajax({
						type: "GET",
						async: false,
						url: courseURL,
						success: function(response){
								jsonResponse = JSON.parse(response);
						}
				}
		);
		
		// Loop through all courses
		for(var i = 0; i < jsonResponse.OfferedCourses.course.length; i++){
				var sectionDataLength = jsonResponse.OfferedCourses.course[i].CourseData.SectionData.length;
				if(sectionDataLength == undefined){
						// Perform check on single section id
						if( jsonResponse.OfferedCourses.course[i].CourseData.SectionData.id == selectionText){
								// match on selection text
								gatherData(courseURL, termNumber, jsonResponse, i, -1, selectionText);
								return;
						}
				}
				else{
						// Loop through all sections
						for(var j = 0; j < sectionDataLength; j++){
								if(jsonResponse.OfferedCourses.course[i].CourseData.SectionData[j].id == selectionText){
										// match on selection text
										gatherData(courseURL, termNumber, jsonResponse, i, j, selectionText);
										return;
								}
						}
				}
		}
		alert("You made an invalid selection. Pleae select the section you want from the sections collumn.");
}

function gatherData(courseURL, term, jsonResponse, courseIndex, sectionIndex, sectionNumber){
		var department = jsonResponse.Dept_Info.department;
		var deptAbbreviation = jsonResponse.Dept_Info.abbreviation;
		var publishedCourseName = jsonResponse.OfferedCourses.course[courseIndex].PublishedCourseID + ": " + jsonResponse.OfferedCourses.course[courseIndex].CourseData.title;
		var sectionData = "";
		if(sectionIndex == -1)
				sectionData = jsonResponse.OfferedCourses.course[courseIndex].CourseData.SectionData;
		else
				sectionData = jsonResponse.OfferedCourses.course[courseIndex].CourseData.SectionData[sectionIndex];
		
		if(sectionData.canceled == "Y"){
				alert("The section you selected has been canceled");
				return;
		}
		
		var courseType = sectionData.type;
		var instructor = "";
		if(!$.isEmptyObject(sectionData.instructor.first_name))
				instructor = sectionData.instructor.first_name+" "+sectionData.instructor.last_name;
		var courseDays = sectionData.day;
		var startTime = sectionData.start_time;
		var endTime = sectionData.end_time;
		var location = sectionData.location;
		var numberRegistered = sectionData.number_registered;
		var spacesAvailable = sectionData.spaces_available;
		var seatOpen = (Number(spacesAvailable) > Number(numberRegistered));
		if(seatOpen){
				alert("This class has space in it! Go to my usc to get your spot before someone else does!")
				chrome.browserAction.setIcon({path: "../images/icon19.png"});
		}
		else{
				$.post("http://www.hummingbirdapplication.com/application/extension/ex-addClass.php",
						{
								 "term"									: term,
								 "deptAbbreviation"			: deptAbbreviation,
								 "department"						: department,
								 "courseDataURL"				: courseURL,
								 "sectionNumber"				: sectionNumber,
								 "publishedCourseName"	: publishedCourseName,
								 "courseIndex"					: courseIndex,
								 "sectionIndex"					: sectionIndex,
								 "courseType"						: courseType,
								 "instructor"						: instructor,
								 "courseDays"						: courseDays,
								 "startTime"						: startTime,
								 "endTime"							: endTime,
								 "location"							: location,
								 "numberRegistered"			: numberRegistered,
								 "spacesAvailable"			: spacesAvailable,
								 "seatOpen"							: seatOpen
						 },
						 function(response){
								var jsonResponse = JSON.parse(response);
								chrome.browserAction.setIcon({path: "../images/icon19.png"});
								alert(jsonResponse.message);
						 }
				);
		}
}