<?php

		require_once("../global/includes/gbFunctions.php");
		require_once("../global/includes/db-open.php");
		
		
		if(!loggedIn()){
				$response['status'] = "1";
				echo json_encode($response);
				exit(0);
		}
		
		
		// gather all post data
		$term = $_POST['term'];
		$deptAbbreviation = $_POST['deptAbbreviation'];
		$department = $_POST['department'];
		$courseDataURL = $_POST['courseDataURL'];
		$sectionNumber = intval($_POST['sectionNumber'],10);
		$publishedCourseName = $_POST['publishedCourseName'];
		$courseIndex = $_POST['courseIndex'];
		$sectionIndex = $_POST['sectionIndex'];
		$courseType = $_POST['courseType'];
		$instructor = $_POST['instructor'];
		$startTime = $_POST['startTime'];
		$endTime = $_POST['endTime'];
		$location = $_POST['location'];
		$numberRegistered = $_POST['numberRegistered'];
		$spacesAvailable = $_POST['spacesAvailable'];
		$seatOpen = $_POST['seatOpen'];
		
		mysqli_query($link,"START TRANSACTION");
				
		// check if dept exists for this term in departments table
		$query = "SELECT deptAbbreviation FROM departments WHERE term=".$term." AND deptAbbreviation='".$deptAbbreviation."'";
		$result = mysqli_query($link,$query);
		if(mysqli_num_rows($result) == 0){
				
				// use cURL to get term end date
				$destinationURL = "http://web-app.usc.edu/ws/soc/api/session/001/$term";
				
				$ch = curl_init( $destinationURL );
				$options = array(
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_HTTPHEADER => array('Content-type: application/json'),
						CURLOPT_TIMEOUT => 5
				);
				
				// Setting curl options
				curl_setopt_array( $ch, $options );
				
				// Getting results
				$servResponse =  curl_exec($ch); // Getting JSON result string
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
				for($i = 0; $i <= strlen($courseDays); $i++){
						switch (substr($courseDays,$i,1)){
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
						$response['message'] = "The course ".$pcn." was successfully added to your watch-list";
				}
				else{
						mysqli_rollback($sqlLink);
						$response['message'] = "There was an error processing your request. Please try again later.";
				}
				
				echo json_encode($response);
				exit(0);
		}
?>