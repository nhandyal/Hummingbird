<?php
		require_once("../../global/includes/gbFunctions.php");
		
		$targetURL =  "http://web-app.usc.edu/ws/soc/api/depts/".$_GET['term'];
		
		$servResponse = curlURL($targetURL);
		if($servResponse == FALSE)
				exit(0);
		
		$schoolData = json_decode($servResponse)->department;
		
		
		// begin unordered list
		echo "<ul class='ui-drop-menu ui-select' id='ui-dept-select'>";
		foreach($schoolData as $school){
				if(isset($school->department)){
						if(gettype($school->department) == object){
								$deptAbbreviation = $school->department->code;
								$deptName = $school->department->name;
								echo "<li id='$deptAbbreviation'>$deptAbbreviation - $deptName</li>";
						}
						else{
								foreach($school->department as $department){
										$deptAbbreviation = $department->code;
										$deptName = $department->name;
										echo "<li id='$deptAbbreviation'>$deptAbbreviation - $deptName</li>";
								}
						}
				}
		}
		echo "</ul>";

		exit(0);
?>