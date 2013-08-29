<?php

		require_once("../global/includes/db-open.php");
		
		$deleteQuery = "DELETE FROM users WHERE vrf_email=0";
		mysqli_query($link,$deleteQuery);
		
		require_once("../global/includes/db-close.php");
?>