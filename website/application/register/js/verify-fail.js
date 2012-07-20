$(document).ready(function(){
		var wHeight = (($(window).height()-150-($('#page-content').height()))/2)-($('#page-content').height())/2;
		$('#page-content').css({"padding-top": wHeight});
});