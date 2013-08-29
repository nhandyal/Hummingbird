<?php
		
		$error_message = "Enter the email you registered with us to reset your password. Don't have an account? No problem! Click <a href='/' style='text-decoration:none'>here</a> to create one.";
		
		// only if form has been submitted send email to user
		if(isset($_POST['email'])){
				require_once("application/global/includes/db-open.php");
				
				$email = $_POST['email'];
				
				$query = "SELECT email_hash, vrf_email FROM users WHERE email='$email'";
				$result = mysqli_query($link,$query);
				$r = mysqli_fetch_assoc($result);
				if(mysqli_num_rows($result) == 0){
						$error_message = "We're sorry, but that e-mail isn't registered with us. Need to create an account? Click <a href='/' style='text-decoration:none'>here</a> to create one.";
				}
				else if($r['vrf_email'] == 0){
						// account hasn't been verified
						$error_message = "Uhoh, looks like this account hasn't been verified. Check the e-mail you provided for instruction on how to verify your account.";
				}
				else{
						$link = 'http://www.hummingbirdapplication.com/application/reset.php?vrf-email='.$r['email_hash'].'&srvt='.time();
						$to = $email;
						$subject = "Hummingbird Password Reset";
						$headers = "From: no_reply@hummingbirdapplication.com" . "\r\n";
						$headers .= "Reply-To: no_reply@hummingbirdapplication.com" . "\r\n";
						$headers .= "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
						$message = "
								<!DOCTYPE html>
								<html>
										<head>
										</head>
										<body>
												<body style='margin:0px; padding:0px;font-family: Verdana,Arial;font-size:16px ;color:rgb(60,60,60)'>
												<table style='width:600px; margin:auto'>
														<tr>
																<td><img src='http://www.hummingbirdapplication.com/images/logos/hummingbird.png' width='52' height='70' alt='Hummingbird'></td>
																<td style='vertical-align:bottom'><span style='font-size:70px'>HUMMING</span><span style='color:rgb(205,20,20);font-size:70px'>BIRD</span></td>
														</tr>
														<tr style='text-align:justify'>
																<td colspan='2' style='padding-top:20px'>
																		Hi There!<br/><br/>
																		We heard you forgot your password so we're here to help. Simply follow the link below to set your new password and you'll be back using Hummingbird
																		before you know it. Keep in mind that the link is only valid for an hour.
																</td>
														</tr>
														<tr>
																<td colspan='2' style='padding-top:50px; padding-bottom:50px'>
																		<a href='$link'>$link</a>
																</td>
														</tr>
														<tr style='text-align:justify'>
																<td colspan='2' style='padding-top:20px'>
																		If you've remembered your password, or didn't make this request yourself, just feel free to forget about this mail and carry on with your day!
																</td>
														</tr>
														<tr>
																<td colspan='2' style='padding-top:20px'>
																		Cheers,<br/><span style='color:rgb(205,20,20)'>The Hummingbird Team</span>
																</td>
														</tr>
												</table>
										</body>
								</html>
						";
						
						if(mail($to,$subject,$message,$headers)){
								// email sent successfully
								$error_message = "You're all set! You should be recieving an email from us shortly with further instructions.";
						}
						else{
								// email failed to send
								$error_message = "We're sorry, but having a couple technical difficulties at the moment. Please try again in a few minutes";
						}
				}
		}
?>
<!DOCTYPE html>
<html>
		<head>
				<meta charset="UTF-8">
				<title>Reset Password</title>
				<link href='http://fonts.googleapis.com/css?family=Cantarell:400,700' rel='stylesheet' type='text/css'/>
				<link href='css/fgpwd.css' rel='stylesheet' type='text/css'/>
				<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
				<script src="js/fgpwd.js" type="text/javascript"></script>
				<script src='js/g-analytics.js' type='text/javascript'></script>
		</head>
		<body>
				<div id="page-content">
						<p id="title" style='width:700px; margin:auto; padding-bottom:10px'><?php echo $error_message; ?></p>
						<form id='reset-pwd' action='fgpwd.php' method='post'>
								<input type="email" class="input-element" name="email" placeholder="e-mail" required="required"/>
								<input type="submit" class="input-element" id="login-submit" value="Reset Password"/>	
						</form>
				</div>
		</body>
</html>
				
