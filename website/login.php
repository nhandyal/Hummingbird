<?php
		// err = 0 - check login credentials - display err 1 if invalid email/pwd
		// err = 1 - display login screen with invalid email
		// err = 2 - display login screen with invalid pwd
		// err = 3 - display login screen with you must me logged in

		// include gbFunctions.php, gbFunctions starts the session and incldues a logged in function
		require_once("application/global/includes/gbFunctions.php");
		
		//make sure err type is set
		if(!isset($_GET['err'])){
				header( 'Location: /' );
				exit(0);
		}
		
		
		if(loggedIn()){
				header( 'Location: application/main/' );
				exit(0);
		}
		
		$err = $_GET['err'];
		
		// check login credentials only if err = 0
		
		if($err == 0){
				//make sure information was supplied from the form
				if(!isset($_POST['email'])){
						exit(0);
				}
				
				//open connection to the database
				require_once("application/global/includes/db-open.php");
				
				$email = $_POST['email'];
				$pwd = md5($_POST['pwd']);
				$persistence = $_POST['persistence'];
				
				$query = "SELECT pwd, vrf_email FROM users WHERE email='$email'";
				$result = mysqli_query($link,$query);
				
				if(mysqli_num_rows($result) == 0){
						//invalid email
						header( 'Location: login.php?err=1' );
						exit(0);
				}
				
				$r = mysqli_fetch_assoc($result);
				
				// check if email has been verified
				if($r['vrf_email'] != 1){
						// email hasn't been verified
						header( 'Location: login.php?err=5' );
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
						header( 'Location: application/main/' );
						exit(0);
				}
				else{
						header( 'Location: login.php?err=2' );
						exit(0);
				}
		}
				
		$error_message = "";
		
		switch ($err){
				case 1:
						$error_message = "Invalid Email";
						break;
				case 2:
						$error_message = "Invalid Password";
						break;
				case 3:
						$error_message = "You must be logged in to view this page.";
						break;
				case 4:
						$error_message = "Awesome! Your account has been verified, login below to start using Hummingbird.";
						break;
				case 5:
						$error_message = "Uhoh, looks like this account hasn't been verified. Check the e-mail you provided for instructions on how to verify your account.";
						break;
		}
?>

<!DOCTYPE html>
<html>
		<head>
				<title>Login | Hummingbird</title>
				<link href='http://fonts.googleapis.com/css?family=Cantarell:400,700' rel='stylesheet' type='text/css'/>
				<link href='css/login.css' rel='stylesheet' type='text/css'/>
				<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
				<script src="js/login.js" type="text/javascript"></script>
				<script src='js/g-analytics.js' type='text/javascript'></script>
		</head>
		<body>
				<div id="header">
						<div id="header-img-container">
								<a href="/"><img src="images/logos/hummingbird.png" width="74px" height="100px"/></a>
						</div>
				</div>
				<div id="page-content">
						<p id="error-message"><?php echo $error_message; ?></p>
						<div id="login-wrapper">
								<form id="login" action="login.php?err=0" method="post" autocomplete='off'>
										<div class="login-subContainer">
												<input type="email" class="textfields" id="login-email" name="email" placeholder="e-mail" required="required"/>
												<input type="password" class="textfields" id="login-pwd" name="pwd" placeholder="password" required="required"/>
												<input type="submit" id="login-submit" value="login"/>
										</div>
										<div class="login-subContainer">
												<input id="login-persistence" type="checkbox" name="persistence" value="1"/><span id="login-label">Remember me</span>
												<a id="login-fPassword" href="fgpwd.php">Forgot your password?</a>
										</div>
								</form>
						</div>
				</div>
		</body>
</html>