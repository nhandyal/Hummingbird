<?php
		require_once("../global/includes/db-open.php");
		require_once("../global/includes/gbFunctions.php");
		
		$text_notify = $_GET['text_notify'];
		
		switch($text_notify){
				case 'E':
						$text_notify = 1;
						break;
				case 'D':
						$text_notify = 0;
						break;
		}
		
		$updateQuery = "UPDATE users SET text_notify=$text_notify WHERE email='".userEmail()."'";
		mysqli_query($link,$updateQuery);
		
		echo 0;
		exit(0);
?>