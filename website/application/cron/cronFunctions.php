<?php

		// UPDATE DEPARTMENT DATA

		require_once("../global/includes/db-open-as-object.php");
		
		// variables we will later bind to the prepared statement
		$servResponse;
		$term;
		$deptAbbreviation;
		
		// initialize the prepared statement
		$stmt = $link->stmt_init();
		
		if($stmt->prepare("UPDATE departments SET JSONData=?, timeLastUpdated=? WHERE term=? AND deptAbbreviation=?")){
				
				// bind the appropriate variables to the prepared statement
				$stmt->bind_param('siis', $servResponse,$time, $term, $deptAbbreviation);
				
				
				// run the course update code
				$query = "SELECT term, deptAbbreviation, courseDataURL FROM departments WHERE expDate > ".time();
				$result = $link->query($query);
				
				while($r = $result->fetch_assoc()){
						// use cURL to get department JSON data
						$destinationURL = $r['courseDataURL'];
						
						$ch = curl_init( $destinationURL );
						$options = array(
								CURLOPT_RETURNTRANSFER => true,
								CURLOPT_HTTPHEADER => array('Content-type: application/json')
						);
						
						// Setting curl options
						curl_setopt_array( $ch, $options );
						
						// Getting results
						$servResponse =  curl_exec($ch); // Getting jSON result string
						echo substr($servResponse,0,50)."<br/>";
						
						$term = $r['term'];
						$deptAbbreviation = $r['deptAbbreviation'];
						$time = time();
						
						// execute prepared update statement
						$stmt->execute();
				}
				
		}
		
		require_once("../global/includes/db-close-as-object.php");
		
		// FINISH UPDATE DEPARTMENT DATA
		
		// OPEN DB CONNECTION -- PROCEDURAL
		require_once("../global/includes/db-open.php");
		
		// CLEAR USER ENTRIES
		
		$deleteQuery = "DELETE FROM users WHERE vrf_email=0";
		mysqli_query($link,$deleteQuery);
		
		// CHECK COURSE AVAILABILITY
		
		
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
		
		
		// SEND COURSE NOTIFICATIONS
		
		
		$query = "SELECT term, deptAbbreviation, sectionNumber FROM courses WHERE seatOpen=1";
		$course_result = mysqli_query($link,$query);
		
		while($course_r = mysqli_fetch_assoc($course_result)){
				$term = $course_r['term'];
				$deptAbbreviation = $course_r['deptAbbreviation'];
				$sectionNumber = $course_r['sectionNumber'];
				
				
				$userQuery = "SELECT a.email, a.numberOfEmailsSent, b.text_notify, b.phone_number, b.carrier_code, b.vrf_phone FROM user_courses a LEFT JOIN users b ON a.email=b.email WHERE term=$term AND deptAbbreviation='$deptAbbreviation' AND sectionNumber=$sectionNumber AND numberOfEmailsSent<3";
				$user_result = mysqli_query($link,$userQuery);
				
				while($user_r = mysqli_fetch_assoc($user_result)){
						$numberOfEmailsSent = intval($user_r['numberOfEmailsSent'])+1;
						$toEmail = $user_r['email'];
						$emailSubject = "$deptAbbreviation - $sectionNumber Available";
						$emailHeaders = "From: no_reply@hummingbirdapplication.com" . "\r\n";
						$emailHeaders .= "Reply-To: no_reply@hummingbirdapplication.com" . "\r\n";
						$emailHeaders .= "MIME-Version: 1.0" . "\r\n";
						$emailHeaders .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
						$emailMessage = "
								<html>
										<head>
										</head>
										<body style='margin:0px; padding:0px;font-family: Verdana,Arial;font-size:16px ;color:rgb(60,60,60)'>
												<table style='width:600px; margin:auto'>
														<tr>
																<td><img src='http://www.hummingbirdapplication.com/images/logos/hummingbird.png' width='52' height='70' alt='Hummingbird'></td>
																<td style='vertical-align:bottom'><span style='font-size:70px'>HUMMING</span><span style='color:rgb(205,20,20);font-size:70px'>BIRD</span></td>
														</tr>
														<tr style='text-align:justify'>
																<td colspan='2' style='padding-top:20px'>
																		Hey you! It's your lucky day, the class you've been waiting for is finally available. 
																</td>
														</tr>
														<tr>
																<td colspan='2' style='padding-top:50px; padding-bottom:50px'>
																		$deptAbbreviation - $sectionNumber
																		</br>
																		<a href='https://my.usc.edu/portal/guest.php'>My USC Login</a>
																</td>
														</tr>
														<tr>
																<td colspan='2' style='padding-top:20px'>
																		Cheers,<br/><span style='color:rgb(205,20,20)'>The Hummingbird Team</span>
																		<br/>
																		<br/>
																</td>
														</tr>
												</table>
										</body>
								</html>
								";
						
						mail($toEmail,$emailSubject,$emailMessage,$emailHeaders);
						
						if($user_r['text_notify'] == 1){
								$toPhone = $user_r['phone_number'].$user_r['carrier_code'];
								$textHeaders = "From: no_reply@hummingbirdapplication.com" . "\r\n";
								$textMessage = "Course available ".$deptAbbreviation." - ".$sectionNumber;
								ini_set('sendmail_from','no_reply@hummingbirdapplication.com');
								mail($toPhone,"",$textMessage,$textHeaders,"-fno_reply@hummingbirdapplication.com");
						}
						
						$updateQuery = "UPDATE user_courses SET numberOfEmailsSent=$numberOfEmailsSent WHERE term=$term AND deptAbbreviation='$deptAbbreviation' AND sectionNumber=$sectionNumber";
						mysqli_query($link,$updateQuery);
				}
		}
		
		// CLOSE DB CONNECTION -- PROCEDURAL
		require_once("../global/includes/db-close.php");
		
		echo "<br/><br/>Cron Completed At: ".date("m-d-y -- h:i A" ,time());
		
?>