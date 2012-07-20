<?php

		require_once("../global/includes/db-open-as-object.php");
		
		// variables we will later bind to the prepared statement
		$servResponse;
		$term;
		$deptAbbreviation;
		
		// initialize the prepared statement
		$stmt = $link->stmt_init();
		
		if($stmt->prepare("UPDATE departments SET JSONData=? WHERE term=? AND deptAbbreviation=?")){
				
				// bind the appropriate variables to the prepared statement
				$stmt->bind_param('sis', $servResponse, $term, $deptAbbreviation);
				
				
				// run the course update code
				$query = "SELECT term, deptAbbreviation, courseDataURL FROM departments WHERE expDate > ".time();
				$result = $link->query($query);
				
				while($r = $result->fetch_assoc()){
						// use cURL to get department JSON data
						$destinationURL = $r['courseDataURL'];
						
						$ch = curl_init( $destinationURL );
						$options = array(
								CURLOPT_RETURNTRANSFER => true,
								CURLOPT_HTTPHEADER => array('Content-type: application/json'),
								CURLOPT_TIMEOUT => 5
						);
						
						// Setting curl options
						curl_setopt_array( $ch, $options );
						
						// Getting results
						$servResponse =  curl_exec($ch); // Getting jSON result string
						//$servResponse = htmlentities($servResponse); // encode all special characters so they can be properly stored in the db
						
						$term = $r['term'];
						$deptAbbreviation = $r['deptAbbreviation'];
						
						// execute prepared update statement
						$stmt->execute();
				}
				
		}
		
		require_once("../global/includes/db-close-as-object.php");
		
?>