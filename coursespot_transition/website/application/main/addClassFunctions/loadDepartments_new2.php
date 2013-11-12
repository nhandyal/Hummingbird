<?php
		require_once("../../global/includes/gbFunctions.php");
		
		$targetURL =  "http://web-app.usc.edu/ws/soc/api/depts/".$_GET['term'];
		
		$servResponse = curlURL($targetURL);
		if($servResponse == FALSE)
				exit(0);
		
		$universityData = json_decode($servResponse)->department;
		$masterDataSet = NULL;
		
		$i = 0;
		//$deptAbbreviation = $collegeData->department->code;
		//$deptName = $collegeData->department->name;
		foreach($universityData as $collegeData){
			$collegeDepartment = $collegeData->department;
			$collegeDepartmentDataType = gettype($collegeDepartment);
			/*
			 * Three different types of data
			 * object 	-> only one offered department under the college
			 * array 	-> a series of offered departments under the college
			 * NULL 	-> the college is the department
			*/
			
			if(is_object($collegeDepartment)){
				$deptAbbreviation = $collegeDepartment->code;
				$deptName = $collegeDepartment->name;
				$universityData[$i] = array("deptAbr" => $deptAbbreviation, "deptName" => $deptName);
				$i++;
			}
			else if(is_array($collegeDepartment)){
				foreach($collegeDepartment as $department){
					$deptAbbreviation = $department->code;
					$deptName = $department->name;		
					$universityData[$i] = array("deptAbr" => $deptAbbreviation, "deptName" => $deptName);
					$i++;
				}
			}
			else if(is_null($collegeDepartment)){
				$deptAbbreviation = $collegeData->code;
				$deptName = $collegeData->name;
				$universityData[$i] = array("deptAbr" => $deptAbbreviation, "deptName" => $deptName);
				$i++;
			}
		}

		asort($universityData);

		echo "<ul class='ui-drop-menu ui-select' id='ui-dept-select'>";
		foreach ($universityData as $i => $department) {
			echo "<li id='$department[deptAbr]'>$department[deptAbr] - $department[deptName]</li>";
		}
		echo "</ul>";
		
?>