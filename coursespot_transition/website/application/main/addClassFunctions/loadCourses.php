<?php
		// edit
		require_once("../../global/includes/gbFunctions.php");
		
		$targetURL =  "http://web-app.usc.edu/ws/soc/api/classes/".$_GET['dept']."/".$_GET['term'];
		$offeredCourses = json_decode(curlURL($targetURL))->OfferedCourses->course;
		$listData = "";
		$sectionData = "";
		$courseIndex = 0;
		$listData .= "<ul class='ui-drop-menu ui-select' id='ui-course-select'>";
		
		foreach($offeredCourses as $course){
				$sequence = "";
				$suffix = "";
				$courseNumber = $course->CourseData->number;
				$courseTitle = $course->CourseData->title;
				$deptName = $course->CourseData->prefix;
				
				// check if sequence is there
				if(gettype($course->CourseData->sequence) != object)
						$sequence = $course->CourseData->sequence;
				
				// check if suffix is there
				if(gettype($course->CourseData->suffix) != object)
						$suffix = $course->CourseData->suffix;
				
				// add li element with course data
				$courseID = "$courseNumber$sequence$suffix";
				$listData .= "<li id='$deptName-$courseID'>$courseID: $courseTitle</li>";


				// Build sectionData


				$deptName = $course->CourseData->prefix;
				$courseNumber = $course->CourseData->number;
																															// OPEN CONTAINMENT DIV TAG			
				$sectionData .= "<div id='$deptName-$courseID-data'>"; 													// this div is the hidden container. We will take its contents
																															// and insert them into the section-data-container. ID = deptName-courseNumber-D=data.
																															// referenced by department li select id.
				$sectionData .= "<ul class='ui-drop-menu ui-select section-data-ul'>";
				if(gettype($course->CourseData->SectionData) == object){
						// only one offered section
						
						// Gather all data to be proccessed
						$type = $course->CourseData->SectionData->type;
						$time = strval($course->CourseData->SectionData->start_time)."-".strval($course->CourseData->SectionData->end_time);
						$days = "";
						$temp = (array)$course->CourseData->SectionData->day;
						if(!empty($temp))
								$days = $course->CourseData->SectionData->day;
						
						$instructor = "";
						$sectionNumber = $course->CourseData->SectionData->id;
						
						$spaceAvailable = ($course->CourseData->SectionData->number_registered < $course->CourseData->SectionData->spaces_available);
						$liClass = "";
						if($spaceAvailable)
								$liClass .= "class='section-data-li section-available'";
						else
								$liClass .= "class='section-data-li section-addable' onclick='confirmCourseAdd(this)'";
								
						if($type == "Lec"){
								$temp = ((array)$course->CourseData->SectionData->instructor->first_name);
								if(!empty($temp))
										$instructor = $course->CourseData->SectionData->instructor->last_name;
						}
						
						$sectionData .= "<li $liClass><div>";																		// open the li tag and the visible data div tag
						$sectionData .= "<div class='section-data-content data-sectionNumber'>$sectionNumber</div>";				// add data to the visible div tag
						$sectionData .= "<div class='section-data-content data-type'>$type</div>";								
						$sectionData .= "<div class='section-data-content data-time'>$time</div>";
						$sectionData .= "<div class='section-data-content data-days'>$days</div>";
						$sectionData .= "<div class='section-data-content data-instructor'>$instructor</div></div>";				// add final visible data elements and close visible data container div
						

						$sectionData .= "<input type='hidden' class='course-index' value='$courseIndex'/>";
						$sectionData .= "<input type='hidden' class='section-index' value='-1'/>";
						
						$sectionData .= "</li>";
				}
				else{
						// multiple offered sections
						$sectionIndex = 0;
						
						foreach($course->CourseData->SectionData as $section){
								
								// Gather all data to be proccessed
								$type = $section->type;
								$time = strval($section->start_time)."-".strval($section->end_time);
								$days = "";
								$temp = (array)$section->day;
								if(!empty($temp))
										$days = $section->day;
								
								$instructor = "";
								$sectionNumber = $section->id;
								
								$spaceAvailable = ($section->number_registered < $section->spaces_available);
								$liClass = "";
								if($spaceAvailable)
										$liClass .= "class='section-data-li section-available'";
								else
										$liClass .= "class='section-data-li section-addable' onclick='confirmCourseAdd(this)'";
								
								if($type == "Lec"){
										$temp =((array)$section->instructor->first_name);
										if(!empty($temp))
												$instructor = strval($section->instructor->last_name);
								}
								
								$sectionData .= "<li $liClass><div>";																						// open the li tag and the visible data div tag
								$sectionData .= "<div class='section-data-content data-sectionNumber'>$sectionNumber</div>";				// add data to the visible div tag
								$sectionData .= "<div class='section-data-content data-type'>$type</div>";								
								$sectionData .= "<div class='section-data-content data-time'>$time</div>";
								$sectionData .= "<div class='section-data-content data-days'>$days</div>";
								$sectionData .= "<div class='section-data-content data-instructor'>$instructor</div></div>";		// add final visible data elements and close visible data container div
								
								
								$sectionData .= "<input type='hidden' class='course-index' value='$courseIndex'/>";
								$sectionData .= "<input type='hidden' class='section-index' value='$sectionIndex'/>";
								
								$sectionData .= "</li>";
								
								$sectionIndex += 1;
						}
				}
				
				// close ul tag and div container tag
				$sectionData .="</ul></div>";
				
				$courseIndex += 1;
		}

		$listData .= "</ul>";
		$response['listData'] = $listData;
		$response['sectionData'] = $sectionData;
		
		echo json_encode($response);
		
		exit(0);
?>