<?php
		require_once("../global/includes/db-open.php");
		require_once("../global/includes/gbFunctions.php");
		
		$response = "";
		$query = "SELECT user_num FROM users WHERE email='".userEmail()."' AND phone_vrf_code='".$_GET['phone_vrf_code']."'";
		$result = mysqli_query($link,$query);
		
		if(mysqli_num_rows($result) == 1){
				$updateQuery = "UPDATE users SET vrf_phone=1, text_notify=1 WHERE email='".userEmail()."'";
				mysqli_query($link,$updateQuery);
				$response['status'] = 0;
		}
		else
				$response['status'] = 1;
				
		echo json_encode($response);
		exit(0);
?>