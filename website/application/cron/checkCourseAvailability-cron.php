<?php

		require_once("../global/includes/db-open.php");
		
		
		$query = "SELECT term, deptAbbreviation, JSONData FROM departments";
		$department_result = mysqli_query($link, $query);
		
		while($department_r = mysqli_fetch_assoc($department_result)){
				$term = $department_r['term'];
				$deptAbbreviation = $department_r['deptAbbreviation'];
				$departmentData = json_decode($department_r['JSONData']);
				
				
				$query = "SELECT sectionNumber, courseIndex, sectionIndex FROM courses WHERE term=$term AND deptAbbreviation='$deptAbbreviation'";
				$course_result = mysqli_query($link,$query);
				while($course_r = mysqli_fetch_assoc($course_result)){
						$sectionNumber = $course_r['sectionNumber'];
						$courseIndex = $course_r['courseIndex'];
						$sectionIndex = $course_r['sectionIndex'];
						
						
						$numberRegistered;
						$spacesAvailable;
						$seatOpen = 0;
						if($sectionIndex == -1){
								$numberRegistered = $departmentData->OfferedCourses->course[$courseIndex]->CourseData->SectionData->number_registered;
								$spacesAvailable = $departmentData->OfferedCourses->course[$courseIndex]->CourseData->SectionData->spaces_available;
						}
						else{
								$numberRegistered = $departmentData->OfferedCourses->course[$courseIndex]->CourseData->SectionData[$sectionIndex]->number_registered;
								$spacesAvailable = $departmentData->OfferedCourses->course[$courseIndex]->CourseData->SectionData[$sectionIndex]->spaces_available;
						}
						
						if($numberRegistered < $spacesAvailable)
								$seatOpen = 1;
						
						$courseUpdate = "UPDATE courses SET numberRegistered=$numberRegistered, seatOpen=$seatOpen WHERE term=$term AND deptAbbreviation='$deptAbbreviation' AND sectionNumber=$sectionNumber";
						mysqli_query($link,$courseUpdate) or die(mysqli_error($link)." - ".$courseUpdate);
				}
		}
		
		require_once("../global/includes/db-close.php");
		print_r($link);
?>