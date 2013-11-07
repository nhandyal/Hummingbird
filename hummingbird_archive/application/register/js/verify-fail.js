$(document).ready(function(){
		var wHeight = ($(window).height()-200-($('#page-content').height()))/2;
		$('#page-content').css({"padding-top": wHeight});
});