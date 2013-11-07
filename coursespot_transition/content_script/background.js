chrome.extension.onRequest.addListener(function(type, sender, sendResponse){
		chrome.tabs.create({url : "http://coursespot.net/fgpwd.php"});
});