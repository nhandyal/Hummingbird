<?php
		require_once("../global/includes/db-open.php");
		require_once("../global/includes/gbFunctions.php");
		
		$response = "";
		
		$deleteQuery = "DELETE FROM user_courses WHERE email='".$_GET['email']."' AND term=".$_GET['term']." AND deptAbbreviation='".$_GET['deptAbbreviation']."' AND sectionNumber=".$_GET['sectionNumber'];
		
		mysqli_query($link, $deleteQuery);
		if(mysqli_errno($link) == 0){
				$response['status'] = 0;
		}
		else{
				$response['status'] = 1;
		}
		
		echo json_encode($response);
		exit(0);
?>