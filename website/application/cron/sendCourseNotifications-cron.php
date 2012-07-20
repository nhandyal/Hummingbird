<?php

		require_once("../global/includes/db-open.php");
		
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
		
		require_once("../global/includes/db-close.php");

?>