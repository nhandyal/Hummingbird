<?php
		require_once("../global/includes/db-open.php");
		require_once("../global/includes/gbFunctions.php");
		
		$phone_number = $_GET['phone_number'];
		$carrier = $_GET['carrier'];
		$carrier_code = "";
		
		switch($carrier){
				case "AT&T":
						$carrier_code = "@txt.att.net";
						break;
				case "MetroPCS":
						$carrier_code = "@mymetropcs.com";
						break;
				case "Sprint":
						$carrier_code = "@messaging.sprintpcs.com";
						break;
				case "T-Mobile":
						$carrier_code = "@tmomail.net";
						break;
				case "U.S. Cellular":
						$carrier_code = "@email.uscc.net";
						break;
				case "Verizon":
						$carrier_code = "@vtext.com";
						break;
				default:
						exit(0);
		}
		
		$phone_vrf_code = rand(1000,9999);
		$to = $phone_number.$carrier_code;
		$headers = "From: no_reply@coursespot.net" . "\r\n";
		$message = "Coursespot mobile confirmation code: ".$phone_vrf_code;
		
		$updateQuery = "UPDATE users SET phone_number='".$phone_number."', carrier='".$carrier."', carrier_code='".$carrier_code."', phone_vrf_code='".$phone_vrf_code."' WHERE email='".userEmail()."'";
		mysqli_query($link,$updateQuery);
		ini_set('sendmail_from','no_reply@coursespot.net');
		$response['status'] = (mail($to,"",$message,$headers,"-fno_reply@coursespot.net"));
		echo json_encode($response);
		exit(0);
?>