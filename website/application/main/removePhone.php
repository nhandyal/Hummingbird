<?php
		require_once("../global/includes/db-open.php");
		require_once("../global/includes/gbFunctions.php");
		
		$updateQuery = "UPDATE users SET text_notify=0, phone_number='', carrier='', carrier_code='', vrf_phone=0, phone_vrf_code='' WHERE email='".userEmail()."'";
		mysqli_query($link,$updateQuery);
		
		echo 0;
		exit(0);
?>