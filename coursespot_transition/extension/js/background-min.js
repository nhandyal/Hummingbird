function clickCallBack(a){chrome.browserAction.setIcon({path:"../images/icon19_yellow.png"});if(checkLogIn()!="1"){chrome.browserAction.setIcon({path:"../images/icon19.png"});chrome.tabs.create({url:"http://www.hummingbirdapplication.com/login.php?err=3"})}else{var b=a.selectionText;if(validSelection(b)){b=b.replace("D","");b=b.replace("R","");var c=a.pageUrl.replace("http://web-app.usc.edu/soc/","");var d=c.substring(0,c.indexOf("/"));var e=c.substring(c.indexOf("/"));var f="http://web-app.usc.edu/ws/soc/api/classes"+e+"/"+d;validateSectionNumber(f,b,d)}}}function checkLogIn(){var a;$.ajax({type:"GET",async:false,url:"http://www.hummingbirdapplication.com/application/extension/ex-loggedIn.php",success:function(b){a=b}});return a}function validSelection(a){if(a.length==5||a.length==6){return true}else{alert("You made an invalid selection. Pleae select the section you want from the sections collumn.");return false}}function validateSectionNumber(a,b,c){var d;$.ajax({type:"GET",async:false,url:a,success:function(a){d=JSON.parse(a)}});for(var e=0;e<d.OfferedCourses.course.length;e++){var f=d.OfferedCourses.course[e].CourseData.SectionData.length;if(f==undefined){if(d.OfferedCourses.course[e].CourseData.SectionData.id==b){gatherData(a,c,d,e,-1,b);return}}else{for(var g=0;g<f;g++){if(d.OfferedCourses.course[e].CourseData.SectionData[g].id==b){gatherData(a,c,d,e,g,b);return}}}}alert("You made an invalid selection. Pleae select the section you want from the sections collumn.")}function gatherData(a,b,c,d,e,f){var g=c.Dept_Info.department;var h=c.Dept_Info.abbreviation;var i=c.OfferedCourses.course[d].PublishedCourseID+": "+c.OfferedCourses.course[d].CourseData.title;var j="";if(e==-1)j=c.OfferedCourses.course[d].CourseData.SectionData;else j=c.OfferedCourses.course[d].CourseData.SectionData[e];if(j.canceled=="Y"){alert("The section you selected has been canceled");return}var k=j.type;var l="";if(!$.isEmptyObject(j.instructor.first_name))l=j.instructor.first_name+" "+j.instructor.last_name;var m=j.day;var n=j.start_time;var o=j.end_time;var p=j.location;var q=j.number_registered;var r=j.spaces_available;var s=Number(r)>Number(q);if(s){alert("This class has space in it! Go to my usc to get your spot before someone else does!");chrome.browserAction.setIcon({path:"../images/icon19.png"})}else{$.post("http://www.hummingbirdapplication.com/application/extension/ex-addClass.php",{term:b,deptAbbreviation:h,department:g,courseDataURL:a,sectionNumber:f,publishedCourseName:i,courseIndex:d,sectionIndex:e,courseType:k,instructor:l,courseDays:m,startTime:n,endTime:o,location:p,numberRegistered:q,spacesAvailable:r,seatOpen:s},function(a){var b=JSON.parse(a);chrome.browserAction.setIcon({path:"../images/icon19.png"});alert(b.message)})}}chrome.contextMenus.create({title:"Monitor with Hummingbird",type:"normal",contexts:["selection"],documentUrlPatterns:["http://web-app.usc.edu/soc/*"],onclick:clickCallBack});