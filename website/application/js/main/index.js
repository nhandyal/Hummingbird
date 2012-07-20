$.fn.exists = function () {
		return this.length !== 0;
}

jQuery.expr[':'].contains = function(a, i, m) {
		return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
};

$(document).ready(function(){
		initializePage();

		$('#settings-toggle').click(function(){
				if($('.settings-visible').length == 0){
						$('#settings').css("display","inline-block");
						$('#settings-toggle').addClass("settings-visible");
				}
				else{
						$('#settings').css("display","none");
						$('#settings-toggle').removeClass("settings-visible");
				}
		});
		
		// Add class container visibility toggle
		$('#header-title').click(function(){
				if($('.add-class-visible').length == 0){
						if($('#welcome-wrapper').length != 0){
								$('#welcome-wrapper').fadeOut(350,function(){
										$('#hummingbird-logo').css({'display':'block'});
										$('#welcome-wrapper').remove();
								});
						}
						$('#add-class-container').animate({'height':600},500,function(){
								$('#header-title').addClass('add-class-visible');
						});
						$('#hummingbird-logo').animate({'padding-left':150},500,function(){});
						
						// toggle visibility of black overlay wrapper
						$('#black-overlay').fadeIn(500);
				}
				else{
						$('#add-class-container').animate({'height':0},500,function(){
								$('#header-title').removeClass('add-class-visible');
						});
						$('#hummingbird-logo').animate({'padding-left':0},500,function(){});
						
						// toggle visibility of black overlay wrapper
						$('#black-overlay').fadeOut(500);
				}
		});
		
		// Add class container ui-x close container
		$('#close-add-container').click(function(){
				$('#add-class-container').animate({'height':0},500,function(){
						$('#header-title').removeClass('add-class-visible');
				});
				$('#hummingbird-logo').animate({'padding-left':0},500,function(){});
				
				// toggle visibility of black overlay wrapper
				$('#black-overlay').fadeOut(500);
				return false;
		});
		
		$('#select-term-container').click(function(){
				if($('.term-container-visible').length == 0){
						var containerPosition = $('#select-term-container').position();
						var cssOBJ = {
								'top'				: containerPosition.top,
								'left'			: containerPosition.left,
								'display'		:	'inline-block'
						}
						$('#term-container').css(cssOBJ).addClass('term-container-visible');
				}
				else{
						$('#term-container').css({'display':'none'}).removeClass('term-container-visible');
				}
		});
		
		
		// load departments based on term selection
		$('#ui-term-select li').click(function(){
				$('#selected-term').html($(this).html());
				$('#term-container').css({'display':'none'}).removeClass('term-container-visible');
				$('#selected-term-number').val($(this).attr('id'));
				
				// clear course and section data
				$('#course-data-container').html("");
				$('#section-data-container').html("");
				$('#course-search').attr('readonly','readonly');
				
				// insert loading gif
				$('#department-data-container').html($('#hidden-loading-gif').html());
				loadDepartments();
		});
		
		
		// department live search
		$('#department-search').keyup(function(){
				if($('#department-search').val() != $('#department-search-preVal').val()){
						var searchVal = $('#department-search').val();
						$('#department-search-preVal').val(searchVal);
						if(searchVal != ""){
								$('#ui-dept-select li:not(:contains("'+searchVal+'"))').css({'display':'none'});
								$('#ui-dept-select li:contains("'+searchVal+'")').css({'display':'block'});
						}
						else{
								$('#ui-dept-select li').css({'display':'block'});
						}
				}
		});
		
		// course live search
		$('#course-search').keyup(function(){
				if($('#course-search').val() != $('#course-search-preVal').val()){
						var searchVal = $('#course-search').val();
						$('#course-search-preVal').val(searchVal);
						if(searchVal != ""){
								$('#ui-course-select li:not(:contains("'+searchVal+'"))').css({'display':'none'});
								$('#ui-course-select li:contains("'+searchVal+'")').css({'display':'block'});
						}
						else{
								$('#ui-course-select li').css({'display':'block'});
						}
				}
		});
		
});


function initializePage(){
		// Set the location of the settings box
		var stPos = $('#settings-toggle').position();
		var cssOBJ = {
				'top'		: stPos.top+30,
				'left'	: stPos.left-161
		};
		$('#settings').css(cssOBJ);
		
		
		// Expand letters to fill course title header div
		$('.course-header').each(function(){
				var headerContent = $(this).html();
				var headerLength = headerContent.length;
				var characterWidth = 200/headerLength;
				var headerHTML = "";
				for (var i = 0; i < headerLength; i++){
						headerHTML += "<span style='width:"+characterWidth+"px; display:inline-block'>"+headerContent.charAt(i)+"</span>";
				}
				$(this).html(headerHTML);
		});
		setCourseDataInitial();
}

function scrollSlider(){
		if($('.scroll-first').length != 0){
				var scrollFirstPosition = -($('.scroll-first').position().top);
				$('#course-slider-container').animate({top:scrollFirstPosition},750,function(){});
		}
}

function setCourseDataInitial(){
		var next = $('.scroll-first');
		var i = 0;
		do{
				var courseContent = '#course-content-'+i+' .course-content';
				var refrencedCourse = '#'+$(next).find('.course-data-id').val();
				$(courseContent).html($(refrencedCourse).html());
				next = $(next).next();
				i++;
		}while($(next).exists() && i < 4);
		
		for(var j = i; j < 4; j++){
				$('#transition-element-'+j).css({'opacity':0});
				$('#course-content-'+j).css({'opacity':0});
		}
}

function setCourseData(){
		$('.course-content').animate({'opacity':0},250,function(){
				var next = $('.scroll-first');
				var i = 0;
				do{
						var courseContent = '#course-content-'+i+' .course-content';
						var refrencedCourse = '#'+$(next).find('.course-data-id').val();
						$(courseContent).html($(refrencedCourse).html());
						next = $(next).next();
						i++;
				}while($(next).exists() && i < 4);
				
				for(var j = 0; j < 4; j++){
						if(j < i){
								$('#transition-element-'+j).animate({'opacity':1},250);
								$('#course-content-'+j).animate({'opacity':1},250);
								$('#course-content-'+j+' .course-content').animate({'opacity':1},250);
						}
						else{
								$('#transition-element-'+j).animate({'opacity':0},250);
								$('#course-content-'+j).animate({'opacity':0},250);
						}
				}
		});
}

function scrollDown(){
		var next = $('.scroll-first').next().next().next().next();
		if ($(next).exists()){
				next = $(next).prev();
				$('.scroll-first').removeClass('scroll-first');
				$(next).addClass('scroll-first');
				scrollSlider();
				setCourseData();
		}
}

function scrollUp(){
		var prev = $('.scroll-first').prev().prev().prev();
		if ($(prev).exists()){
				$('.scroll-first').removeClass('scroll-first');
				$(prev).addClass('scroll-first');
				scrollSlider();
				setCourseData();
		}
}

function confirmDeleteCourse(email, term, deptAbbreviation, sectionNumber, type){
		var onClickFunction = "deleteCourse('"+email+"','"+term+"','"+deptAbbreviation+"','"+sectionNumber+"')";
		var fancyBoxHTML = "<div style='width:400px; height:100px'><div style='text-align:center'>You sure you want to delete this class bro?</div>";
		fancyBoxHTML += "<table style='width:75%; margin:auto; text-align:center'><tr id='fancybox-table-header'>";
		fancyBoxHTML += "<td>Course</td>";
		fancyBoxHTML += "<td>Type</td>";
		fancyBoxHTML += "<td>Section</td>";
		fancyBoxHTML += "</tr><tr style='font-size:12px'>";
		fancyBoxHTML += "<td>"+deptAbbreviation+"</td>";
		fancyBoxHTML += "<td>"+type+"</td>";
		fancyBoxHTML += "<td>"+sectionNumber+"</td>";
		fancyBoxHTML += "</tr><tr>";
		fancyBoxHTML += "<td colspan='3' id='fancybox-button-container'><div id='fancybox-button' class='unselectable' style='margin-top:5px' onclick=\""+onClickFunction+"\">Delete Class</div></td>";
		fancyBoxHTML += "</tr></table></div>";
		
		$.fancybox({
				'content' 					: fancyBoxHTML,
				'overlayOpacity'		: 0.7
		});
}

function deleteCourse(email,term, deptAbbreviation, sectionNumber){
		var fancyBoxHTML = "<div style='width:400px'><div style='width:50px; margin:auto'><img src='../images/loading.gif' width='50' height='50' style='opacity:0.3'/></div></div>";
		$.fancybox({
				'content'					: fancyBoxHTML,
				'overlayOpacity'	: 0.7
		});
		$.get('removeCourse.php',{
						'email'							: email,
						'term'							: term,
						'deptAbbreviation'	: deptAbbreviation,
						'sectionNumber'			: sectionNumber
				},function(response){
						var jsonResponse = JSON.parse(response);
						if(jsonResponse.status == 0){
								window.location.reload();
						}
						else{
								var fancyBoxHTML = "<div style='font-size:14px; text-align:center; width:400px'>Uhoh, looks like we're experiencing some technical difficulties. Please try again later.</div>";
								$.fancybox({
										'content'					: fancyBoxHTML,
										'overlayOpacity'	: 0.7
								});
						}
				}
		);
}

function loadDepartments(){
		var term = $('#selected-term-number').val();
		$.get("addClassFunctions/loadDepartments.php",{'term':term},function(response){
				$('#department-data-container').html(response).css({'display':'inline-block'});
				$('#department-search').removeAttr('readonly');
				
				// add a click listener to the li elements
				$('#ui-dept-select li').click(function(){
						$('#selected-department').val($(this).attr('id'));
						loadCourses(this);
				});
		});
}

function loadCourses(callingObject){
		// highlight the selected item
		$('.ui-dept-selected').removeClass('ui-dept-selected');
		$(callingObject).addClass('ui-dept-selected');
		
		// clear section data
		$('#section-data-container').html("");
		
		// add loading gif
		$('#course-data-container').html($('#hidden-loading-gif').html());
		
		// request course data based on selected department
		var term = $('#selected-term-number').val();
		var dept = ($(callingObject).attr('id')).toLowerCase();
		$.get('addClassFunctions/loadCourses.php',{
						'term' 		: term,
						'dept'		: dept
				},
				function(response){
						var jsonResponse = JSON.parse(response);
						$('#select-course-container').css({'opacity':1});
						$('#course-data-container').html(jsonResponse.listData).css({'display':'inline-block'});
						$('#course-search').removeAttr('readonly');
						
						// add hidden section data to container
						$('#hidden-section-data').html(jsonResponse.sectionData);
						
						// add a click listener to the course li elements
						$('#ui-course-select li').click(function(){
								displayCourseData(this);
						});
				}
		);
}

function displayCourseData(callingObject){
		$('.ui-course-selected').removeClass('ui-course-selected');
		$(callingObject).addClass('ui-course-selected');
		
		// load data into section browser
		var sectionDataID = $(callingObject).attr('id')+"-data";
		$('#section-data-container').html($('#'+sectionDataID).html()).css({'display':'block'});
}

function confirmCourseAdd(obj){
		var courseIndex = $(obj).children('.course-index').val();
		var sectionIndex = $(obj).children('.section-index').val();
		var dataContainer = $(obj).children('div');
		var sectionNumber = $(dataContainer).children('.data-sectionNumber').text();
		var type = $(dataContainer).children('.data-type').text();
		var time = $(dataContainer).children('.data-time').text();
		var days = $(dataContainer).children('.data-days').text();
		var instructor = $(dataContainer).children('.data-instructor').text();
		
		var fancyBoxHTML = "<table id='fancybox-addCourse-table'>";
		fancyBoxHTML += "<tr><td colspan='5' id='fancybox-table-description'>You sure bro?</td></tr>";
		fancyBoxHTML += "<tr id='fancybox-table-header'>";
		fancyBoxHTML += "<td>Section</td>";
		fancyBoxHTML += "<td>Type</td>";
		fancyBoxHTML += "<td>Time</td>";
		fancyBoxHTML += "<td>Days</td>";
		fancyBoxHTML += "<td>Instructor</td>";
		fancyBoxHTML += "</tr><tr>";
		fancyBoxHTML += "<td>"+sectionNumber+"</td>";
		fancyBoxHTML += "<td>"+type+"</td>";
		fancyBoxHTML += "<td>"+time+"</td>";
		fancyBoxHTML += "<td>"+days+"</td>";
		fancyBoxHTML += "<td>"+instructor+"</td>";
		fancyBoxHTML += "</tr><tr>";
		fancyBoxHTML += "<td colspan='5' id='fancybox-button-container'><div id='fancybox-button' class='unselectable' onclick='addCourse()'>ADD CLASS";
		fancyBoxHTML += "<input type='hidden' id='fancybox-courseIndex' value='"+courseIndex+"'/>";
		fancyBoxHTML += "<input type='hidden' id='fancybox-sectionIndex' value='"+sectionIndex+"'/></div></td>";
		fancyBoxHTML += "</tr></table>";
		
		$.fancybox({
				'content'					: fancyBoxHTML,
				'overlayOpacity'	: 0.7
		});
}

function addCourse(){
		var fancyBoxHTML = "<div style='width:400px'><div style='width:50px; margin:auto'><img src='../images/loading.gif' width='50' height='50' style='opacity:0.3'/></div></div>";
		$.fancybox({
				'content'					: fancyBoxHTML,
				'overlayOpacity'	: 0.7
		});
		$.get('addClassFunctions/addClass.php',{
						'courseIndex'		: $('#fancybox-courseIndex').val(),
						'sectionIndex'	: $('#fancybox-sectionIndex').val(),
						'term'					: $('#selected-term-number').val(),
						'dept'					: $('#selected-department').val()
				},
				function(response){
						var jsonResponse = JSON.parse(response);
						if(jsonResponse.status == 0){
								$.fancybox.close();
								window.location.reload();
						}
						else{
								var fancyBoxHTML = "<div style='font-size:14px; text-align:center; width:400px'>"+jsonResponse.message+"</div>";
								$.fancybox({
										'content'					: fancyBoxHTML,
										'overlayOpacity'	: 0.7
								});
						}
				}
		);
}