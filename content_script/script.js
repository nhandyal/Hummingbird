var pgURL = window.location.href;
pgURL = pgURL.replace("http://web-app.usc.edu/soc/","");
var termNumber = pgURL.substr(0,5);
var departmentName = pgURL.substr(6);


$.get("http://web-app.usc.edu/ws/soc/api/classes/"+departmentName+"/"+termNumber,{},function(response){
		
});