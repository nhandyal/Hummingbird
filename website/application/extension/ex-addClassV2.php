<?php
		require_once("../global/includes/db-open.php");
		require_once("../global/includes/gbFunctions.php");
		
		// gather all get data
		$courseIndex = intval($_GET['courseIndex']);
		$sectionIndex = intval($_GET['sectionIndex']);
		$term = $_GET['term'];
		$deptAbbreviation = strtoupper($_GET['dept']);
		$courseDataURL = "http://web-app.usc.edu/ws/soc/api/classes/".strtolower($deptAbbreviation)."/$term";
		$sectionData;
		
		// Collect all data to be processed
		$servResponse = curlURL($courseDataURL);
		if($servResponse == FALSE){
				$response['status'] = 1;
				$response['message'] = "There was an error processing your request. Please try again later.";
				echo json_encode($response);
				exit(0);
		}
		
		$departmentData = json_decode($servResponse);
		$publishedCourseName = $departmentData->OfferedCourses->course["$courseIndex"]->PublishedCourseID.": ".$departmentData->OfferedCourses->course["$courseIndex"]->CourseData->title;
		$department = $departmentData->Dept_info->department;
		$sectionData = (array)$departmentData->OfferedCourses->course["$courseIndex"]->CourseData->SectionData;
		if($sectionIndex != -1)
				$sectionData = (array)$sectionData["$sectionIndex"];
				
		$sectionNumber = $sectionData['id'];
		$courseType = $sectionData['type'];
		$days = $sectionData['day'];
		$startTime = $sectionData['start_time'];
		$endTime = $sectionData['end_time'];
		$location = $sectionData['location'];
		$numberRegistered = intval($sectionData['number_registered']);
		$spacesAvailable = intval($sectionData['spaces_available']);
		$seatOpen = 0;
		$instructor = "";
		$temp = (array)$sectionData['instructor']->last_name;
		if(!empty($temp)){
				$instructor = $sectionData['instructor']->first_name." ".$sectionData['instructor']->last_name;
		}
		
		
		// begin checking tables if data is already present
		mysqli_query($link,"START TRANSACTION");
		
		// check if dept exists for this term in departments table
		$query = "SELECT deptAbbreviation FROM departments WHERE term=".$term." AND deptAbbreviation='".$deptAbbreviation."'";
		$result = mysqli_query($link,$query);
		if(mysqli_num_rows($result) == 0){
				$targetURL = "http://web-app.usc.edu/ws/soc/api/session/001/$term";
				$servResponse = curlURL($targetURL);
				
				if($servResponse == FALSE){
						sqlCommRollChanges(1,$link,$publishedCourseName);
				}
				$jsonTermResponse = json_decode($servResponse);
				$expDate = strtotime($jsonTermResponse->end_of_session);
				
				// insert the dept and term into the departments table
				$insertQuery = "INSERT INTO departments (term, deptAbbreviation, department, courseDataURL, expDate) VALUES (".$term.",'".$deptAbbreviation."','".$department."','".$courseDataURL."',".$expDate.")";
				mysqli_query($link,$insertQuery);
				if(mysqli_errno($link) != 0){sqlCommRollChanges(1,$link,$publishedCourseName);}
		}
		
		// check if course exists for this term in the courses table
		$query = "SELECT deptAbbreviation FROM courses WHERE term=".$term." AND deptAbbreviation='".$deptAbbreviation."' AND sectionNumber=".$sectionNumber;
		$result = mysqli_query($link,$query);
		if(mysqli_num_rows($result) == 0){
				// create json encoded string for course days
				$coureDaysArray = "";
				for($i = 0; $i <= strlen($days); $i++){
						switch (substr($days,$i,1)){
								case 'M':
										$coureDaysArray[$i] = "Monday";
										break;
								case 'T':
										$coureDaysArray[$i] = "Tuesday";
										break;
								case 'W':
										$coureDaysArray[$i] = "Wednesday";
										break;
								case 'H':
										$coureDaysArray[$i] = "Thursday";
										break;
								case 'F':
										$coureDaysArray[$i] = "Friday";
										break;
						}
				}
				$courseDays = json_encode($coureDaysArray);
				
				$insertQuery = "INSERT INTO courses (
						term,
						deptAbbreviation,
						sectionNumber,
						publishedCourseName,
						courseIndex,
						sectionIndex,
						createDate,
						courseType,
						instructor,
						courseDays,
						startTime,
						endTime,
						location,
						numberRegistered,
						spacesAvailable,
						seatOpen)
						VALUES
						(".$term.",'"
						.$deptAbbreviation."',"
						.$sectionNumber.",'"
						.$publishedCourseName."',"
						.$courseIndex.","
						.$sectionIndex.","
						."NOW()".",'"
						.$courseType."','"
						.$instructor."','"
						.$courseDays."','"
						.$startTime."','"
						.$endTime."','"
						.$location."',"
						.$numberRegistered.","
						.$spacesAvailable.","
						.$seatOpen.
				")";
				
				mysqli_query($link,$insertQuery);
				if(mysqli_errno($link) != 0){sqlCommRollChanges(1,$link,$publishedCourseName);}
		}
		
		// check if user already has 10 courses in their list
		$query = "SELECT email FROM user_courses WHERE email='".userEmail()."' AND term=".$term;
		$result = mysqli_query($link,$query);
		if(mysqli_num_rows($result) > 10){
				mysqli_rollback($link);
				$response['status'] = 1;
				$response['message'] = "Uhoh! You already have 10 classes on your watch-list for this term. You need to remove a class before you can add some more.";
				echo json_encode($response);
				exit(0);
		}
		// check if user course exists in the user_courses table
		$query = "SELECT email FROM user_courses WHERE email='".userEmail()."' AND term=".$term." AND deptAbbreviation='".$deptAbbreviation."' AND sectionNumber=".$sectionNumber;
		$result = mysqli_query($link,$query);
		if(mysqli_num_rows($result) == 0){
				// insert into user course
				$insertQuery = "INSERT INTO user_courses (email,sectionNumber, deptAbbreviation, term) VALUES ('".userEmail()."',".$sectionNumber.",'".$deptAbbreviation."',".$term.")";
				mysqli_query($link,$insertQuery);
				if(mysqli_errno($link) != 0){sqlCommRollChanges(1,$link,$publishedCourseName);}
				
				// set first_login to false
				$updateQuery = "UPDATE users SET first_login=0 WHERE email='".userEmail()."'";
				mysqli_query($link,$updateQuery);
				if(mysqli_errno($link) != 0){sqlCommRollChanges(1,$link,$publishedCourseName);}
		}
		
		sqlCommRollChanges(0,$link,$publishedCourseName);
		
		// check the sql_error flag to see if there were any errors in the sql queries
		// if there were rollback the changes and echo error
		// else commit changes and echo success
		function sqlCommRollChanges($sql_error, $sqlLink, $pcn){
				$response = "";
				if($sql_error == 0){
						mysqli_commit($sqlLink);
						$response['status'] = 0;
						$response['message'] = "The course ".$pcn." was successfully added to your watch-list";
				}
				else{
						mysqli_rollback($sqlLink);
						$response['status'] = 1;
						$response['message'] = "There was an error processing your request. Please try again later.";
				}
				
				echo json_encode($response);
				exit(0);
		}
?>