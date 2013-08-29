<?php
		require_once("../../global/includes/gbFunctions.php");
		
		$targetURL =  "http://web-app.usc.edu/ws/soc/api/depts/".$_GET['term'];
		
		$servResponse = curlURL($targetURL);
		if($servResponse == FALSE)
				exit(0);
		
		$schoolData = json_decode($servResponse)->department;
		
		$departmentData = NULL;

		// build department data
		$i = 0;
		foreach($schoolData as $school){
			if(gettype($school->department) == object){
				$deptAbbreviation = $school->department->code;
				$deptName = $school->department->name;
			}
			else if($school->department != NULL){
				foreach($school->department as $department){
					$deptAbbreviation = $department->code;
					$deptName = $department->name;
				}
			}
			else{
				$deptAbbreviation = $school->code;
				$deptName = $school->name;
			}
			$departmentData[$i] = array("deptAbr" => $deptAbbreviation, "deptName" => $deptName);
			$i++;
		}

		echo "<ul class='ui-drop-menu ui-select' id='ui-dept-select'>";
		foreach ($departmentData as $i => $department) {
			echo "<li id='$department[deptAbr]'>$department[deptAbr] - $department[deptName]</li>";
		}
		echo "</ul>";
		
		// department data is an array of touples. touple fields = deptAbr, and deptName.
		// run a sort and remove duplicates on the deptAbr field. Asc order

		//qSort($departmentData, 0, $i);
		
		/*
		// remove duplicates
		$trailingIndex = 1;
		for($leadingIndex = 1; $leadingIndex < $i; $leadingIndex++){
			if($departmentData[$leadingIndex].deptAbr != $departmentData[$leadingIndex-1].deptAbr){
				$departmentData[$trailingIndex] = $departmentData[$leadingIndex];
				$trailingIndex++;
			}
		}
		*/

		// build UL of departments
		


		// --------------------------------- END loadDepartment ------------------------------------ //

		function qSort($data, $sI, $size){
			if($size <= 1){
				return;
			}
		
			$eI = $sI + $size - 1;
			$pivotIndex = ($sI+$eI)/2;
			$pivotValue = $data[$pivotIndex];

			$data[$pivotIndex] = $data[$sI];
			$data[$sI] = $pivotValue;

			$upper = $eI;
			$lower = $sI+1;
			while($upper > $lower){
				if($data[$lower].deptAbr > $pivotValue.deptAbr){
					$temp = $data[$upper];
					$data[$upper] = $data[$lower];
					$data[$lower] = $temp;
					$upper--;
				}
				else
					$lower++;
			}

			if($data[$upper] <= $pivotValue){
				$data[$sI] = $data[$upper];
				$data[$upper] = $pivotValue;
			}

			qSort($data, $sI, ($upper-$sI));
			qSort($data, $upper, ($eI-$upper)+1);

			return;
		}
?>