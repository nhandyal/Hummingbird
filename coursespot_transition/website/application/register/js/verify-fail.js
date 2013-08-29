$(document).ready(function(){
		var wHeight = (($(window).height()-($('#page-content').height()))/2)-($('#page-content').height())/2;
		$('#page-content').css({"padding-top": wHeight});
});