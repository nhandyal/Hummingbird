<?php
		$response;
		
		$to = "support@coursespot.net";
		$subject = $_POST['type'];
		$message = $_POST['name']." -- ".$_POST['email']." -- ".date("m-d-y -- h:i A" ,time())."\n".$_POST['message'];
		$headers = "From: ".$_POST['email'] . "\r\n";
		

		// text notify for support
		$to_support = "4084276883@txt.att.net";
		$message_support = "New Support email";

		if(mail($to,$subject,$message,$headers)){
				$response['status'] = 0;
				$response['message'] = "<div id='serv-response'>Thank you for your comments. We value your feedback and hope to make Coursespot better with your support.</div>";
				echo json_encode($response);
				
				mail($to_support, $message);
				exit(0);
		}
		else{
				$response['status'] = 1;
				echo json_encode($response);
				exit(0);
		}
?>