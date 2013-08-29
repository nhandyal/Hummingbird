<?php
		
		
		//either the vrf-email get variable or the pwd post variable has to be set to see this page
		if(!isset($_GET['vrf-email']) && !isset($_POST['pwd'])){
				header( 'Location: /' );
				exit(0);
		}
		
		// if vrf-email is set then check if the link is still valid
		if(isset($_GET['vrf-email'])){
				if(time()-3600 > $_GET['srvt']){
						// link has expired
						header( 'Location: /' );
						exit(0);
				}
		}
		
		
		//open connection to db
		require_once("global/includes/db-open.php");
		if(isset($_GET['vrf-email'])){
				$email_hash = $_GET['vrf-email'];
				$query = "SELECT user_num, first_name FROM users WHERE email_hash='$email_hash'";
				$r = mysqli_fetch_assoc(mysqli_query($link,$query));
				$message = "Hey ".$r['first_name'].", Enter your new password below";
		}
		else if(isset($_POST['pwd'])){
				$user_num = $_POST['ud'];
				$pwd = md5($_POST['pwd']);
				$updateQuery = "UPDATE users SET pwd='$pwd' WHERE user_num=".$user_num;
				mysqli_query($link,$updateQuery);
				if(mysqli_errno($link) != 0){
						$message = "We're sorry but something went wrong and we weren't able to reset your password. Please try again in a little bit.";
				}
				else{
						$message = "Awesome, your password was reset! Return <a href='/' style='color:rgb(205,20,20)'>home</a> to login and continue using Coursespot";
				}
		}
		
?>

<!DOCTYPE html>
<html>
		<head>
				<meta charset="UTF-8">
				<title>Reset Password</title>
				<link href='http://fonts.googleapis.com/css?family=Cantarell:400,700' rel='stylesheet' type='text/css'/>
				<link href='css/reset.css' rel='stylesheet' type='text/css'/>
				<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
				<script src="js/reset-min.js" type="text/javascript"></script>
				<script src="js/g-analytics.js" type="text/javascript"></script>
		</head>
		<body>
				<div id="page-content">
						<p id="title"><?php echo $message; ?></p>
						<?php
								if(isset($_GET['vrf-email'])){
						?>
								<form id='reset-pwd' action='reset.php' method='post'>
										<div class="input-holder">
												<input id="pwd" type="password" class="input-element" name="pwd" placeholder="password" required="required"/>
												<p id="pwd-error" class="error-message">Password must be at least 6 characters long</p>
										</div>
										<div class="input-holder">
												<input id="confirm-pwd" type="password" class="input-element" name="confirm-pwd" placeholder="confirm password" required="required"/>
												<p id="confirm-pwd-error" class="error-message">These passwords don't match. Try Again?</p>
										</div>
										<div class="input-holder">
												<input type="submit" class="input-element" id="login-submit" value="Reset Password"/>
												<p class="error-message">Spacer</p>
										</div>
										<input type="hidden" name="ud" value="<?php echo $r['user_num']; ?>"/>
								</form>
						<?php
								}
						?>
				</div>
		</body>
</html>