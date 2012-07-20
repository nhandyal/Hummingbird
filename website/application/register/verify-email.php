<?php
		require_once("../global/includes/db-open.php");
		
		if(isset($_GET['vrf-email'])){
				$email_hash = $_GET['vrf-email'];
				$updateQuery = "UPDATE users SET vrf_email=1 WHERE email_hash='".$email_hash."'";
				mysqli_query($link,$updateQuery);
				
				if (mysqli_affected_rows($link) != 1){
						header( 'Location: verify-fail.html' ) ;
				}
				else{
						header( 'Location: ../../login.php?err=4' );
				}
		}
		else{
				header( 'Location: /' ) ;
		}
?>