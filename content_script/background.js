chrome.extension.onRequest.addListener(function(type, sender, sendResponse){
		chrome.tabs.create({url : "http://www.hummingbirdapplication.com/login.php?err=3"});
});