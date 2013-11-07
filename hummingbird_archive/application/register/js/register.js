$(document).ready(function(){
		var wHeight = ($(window).height()-150-($('#page-content').height()))/2;
		$('#page-content').css({"padding-top": wHeight});
		
		$('#works-content-wrapper .current').fadeIn(200);
		
		// How it works canvas controls next
		$('#next').click(function(){
				var nextId = '#';
				var currentId = $('#works-content-wrapper .current').attr('id');
				if(currentId == 4)
						nextId += 0;
				else
						nextId += (parseInt(currentId,10) + 1);
				
				currentId = '#'+parseInt(currentId,10);
				
				changeCanvas(currentId,nextId);
				
		});
		
		
		// How it works canvas controls prev
		$('#prev').click(function(){
				var nextId = '#';
				var currentId = $('#works-content-wrapper .current').attr('id');
				if(currentId == 0)
						nextId += 4;
				else
						nextId += (parseInt(currentId,10) - 1);
				
				currentId = '#'+parseInt(currentId,10);
				
				changeCanvas(currentId,nextId);
		});
});

// Brings in the next or prev canvas element for how it works
function changeCanvas(currentId, nextId){
		$(currentId).fadeOut(500,function(){
				$(currentId).removeClass('current');
				$(nextId).fadeIn(700,function(){
						$(nextId).addClass('current');
				});
		});
}