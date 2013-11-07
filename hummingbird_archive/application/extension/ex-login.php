<?php

		require_once("../global/includes/gbFunctions.php");
		
		
		
		$response;
		
		//make sure information was supplied from the form
		if(!isset($_POST['email'])){
				$response["status"] = 1;
				$response["message"] = "Please enter your email.";
				echo json_encode($response);
				exit(0);
		}
		
		//open connection to the database
		require_once("../global/includes/db-open.php");
		
		$email = $_POST['email'];
		$pwd = md5($_POST['pwd']);
		$persistence = $_POST['persistence'];
		
		$query = "SELECT pwd, vrf_email FROM users WHERE email='$email'";
		$result = mysqli_query($link,$query);
		
		if(mysqli_num_rows($result) == 0){
				//invalid email
				$response["status"] = 1;
				$response["message"] = "Invalid Email";
				echo json_encode($response);
				exit(0);
		}
		
		$r = mysqli_fetch_assoc($result);
		
		// check if email has been verified
		if($r['vrf_email'] != 1){
				// email hasn't been verified
				$response["status"] = 1;
				$response["message"] = "Uhoh, looks like this account hasn't been verified. Check the e-mail you provided for instructions on how to verify your account.";
				echo json_encode($response);
				exit(0);
		}
		
		if($r['pwd'] == $pwd){
				// password and email match
				
				// update last login field
				$updateQuery = "UPDATE users SET last_login=NOW() WHERE email='".$email."'";
				mysqli_query($link,$updateQuery);
				
				// depending on persistence variable set cookie or set session
				if($persistence == 1){
						// set cookie
						setcookie("user_email",$email,time()+2592000,'/');
				}
				else{
						// set session
						$_SESSION['user_email'] = $email;
				}
				
				// redirect
				$response["status"] = 0;
				echo json_encode($response);
				exit(0);
		}
		else{
				$response["status"] = 1;
				$response["message"] = "Invalid Password";
				echo json_encode($response);
				exit(0);
		}
		
?>