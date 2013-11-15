<?php
		$response;
		
		$to = "support@coursespot.net";
		$subject = $_POST['type'];
		$message = $_POST['name']." -- ".$_POST['email']." -- ".date("m-d-y -- h:i A" ,time())."\n".$_POST['message'];
		$headers = "From: ".$_POST['email'] . "\r\n";


		if(mail($to,$subject,$message,$headers)){
				$response['status'] = 0;
				$response['message'] = "<div id='serv-response'>Thank you for your comments. We value your feedback and hope to make Coursespot better with your support.</div>";
				

				// text support
				$headers = "From: no_reply@coursespot.net" . "\r\n";
				$message = "New Support Email";		
				ini_set('sendmail_from','no_reply@coursespot.net');		
				$text_status = mail("4084276883@txt.att.net","",$message,$headers,"-fno_reply@coursespot.net");

				$response['test'] = "text notify should have been sent: ".$text_status;
				exit(0);
		}
		else{
				$response['status'] = 1;
				echo json_encode($response);
				exit(0);
		}
?>