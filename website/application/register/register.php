<?php
		require_once("../global/includes/db-open.php");
		
		$first_name = mysqli_real_escape_string($link,$_POST['first-name']);
		$last_name = mysqli_real_escape_string($link,$_POST['last-name']);
		$email = mysqli_real_escape_string($link,$_POST['e-mail']);
		$pwd = (md5($_POST['pwd']));
		$email_hash = md5($email);
		
		$insertQuery = "INSERT INTO users (first_name,last_name,email,pwd,email_hash) VALUES ('".$first_name."','".$last_name."','".$email."','".$pwd."','".$email_hash."')";
		
		
		
		$vrf_link = 'http://www.hummingbirdapplication.com/application/register/verify-email.php?vrf-email='.$email_hash;
		$to = $email;
		$subject = "Welcome to Hummingbird";
		$headers = "From: no_reply@hummingbirdapplication.com" . "\r\n";
		$headers .= "Reply-To: no_reply@hummingbirdapplication.com" . "\r\n";
		$headers .= "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$message = "
				<html>
						<head>
						</head>
						<body style='margin:0px; padding:0px;font-family: Verdana,Arial;font-size:16px ;color:rgb(60,60,60)'>
								<table style='width:600px; margin:auto'>
										<tr>
												<td><img src='http://www.hummingbirdapplication.com/images/logos/hummingbird.png' width='52' height='70' alt='Hummingbird'></td>
												<td style='vertical-align:bottom'><span style='font-size:70px'>HUMMING</span><span style='color:rgb(205,20,20);font-size:70px'>BIRD</span></td>
										</tr>
										<tr style='text-align:justify'>
												<td colspan='2' style='padding-top:20px'>
														Welcome to Hummingbird. We hope you enjoy using this service as much as we enjoyed making it. Before you can get started, we just need to verify your account.
														Click on the link below to finish the activation process. If clicking on the link doesn't work, copy and past the entire url into your browser window and hit go.
												</td>
										</tr>
										<tr>
												<td colspan='2' style='padding-top:50px; padding-bottom:50px'>
														<a href='".$vrf_link."'>".$vrf_link."</a>
												</td>
										</tr>
										<tr style='text-align:center'>
												<td colspan='2'>
														A few things we want you to remember before using Hummingbird.
												</td>
										</tr>
										<tr>
												<td colspan='2' style='padding-top:10px'>
														<ul style='font-size:12px'>
																<li style='padding-top:10px'>We highly recommend using the latest version of <span style='color:rgb(205,20,20)'>Google Chrome</span> or <span style='color:rgb(205,20,20)'>Mozilla Firefox</span> for best results. We realize that for most of you this is already done, but for the few out there who use other browsers... Well we warned you.</li>
																<li style='padding-top:10px'>Humminbird is a <span style='color:rgb(205,20,20)'>course scheduling tool only.</span> Using Hummingbird significantly <span style='color:rgb(205,20,20)'>improves your chances</span> for registering for the classes you want <span style='color:rgb(205,20,20)'>but does not guarantee it</span>. We can help you with your scheduling woes, but it is still ultimately up to you to go register.</li>
																<li style='padding-top:10px'>If you are running Google Chrome, we highly recommend you download and install the <a href='https://chrome.google.com/webstore/detail/adbpoijidlpgogcfalangpplfoigeomn' style='color:rgb(205,20,20)'>Hummingbird Chrome Extension</a>. It's really cool and makes using Hummingbird even easier.</li>
																<li style='padding-top:10px'>This service is currently built for students at The University of Southern California. <span style='color:rgb(205,20,20)'>The app will only work with USC's schedule of classes.</span> If you don't go to USC, you are welcome to try it out and shoot us an email to see if we can bring Hummingbird to your school.</li>
														</ul>
												</td>
										</tr>
										<tr>
												<td colspan='2' style='padding-top:20px'>
														Cheers,<br/><span style='color:rgb(205,20,20)'>The Hummingbird Team</span>
												</td>
										</tr>
										<tr>
												<td colspan='2' style='font-size:12px; padding-top:30px; text-align:center; margin-bottom:50px'>
														This email was meant for ".$first_name." ".$last_name." at ".$email.". If this is not you, or you believe someone stole your identity and signed you up for this service, please disregard this email and we will promptly remove you from our servers.
												</td>
										</tr>
								</table>
						</body>
				</html>
		";
		
		mysqli_query($link,$insertQuery);
		if(mysqli_errno($link) == 1062){
				header( 'Location: register-failed.html' ) ;
		}
		else if(mysqli_errno($link) != 0){
				header( 'Location: ../../' ) ;
		}
		else{
				mail($to,$subject,$message,$headers);
		}
		
		require_once("../global/includes/db-close.php");
?>

<!DOCTYPE html>
<html>
		<head>
				<meta charset="UTF-8"/>
				<title>Verify Email : Hummingbird</title>
				<link href='http://fonts.googleapis.com/css?family=Cantarell:400,700' rel='stylesheet' type='text/css'/>
				<link href='css/register.css' rel='stylesheet' type='text/css'/>
				<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
				<script src="js/register.js" type="text/javascript"></script>
				<script src='js/g-analytics.js' type="text/javascript"></script>
		</head>
		<body>
				<div id="header">
						<div id="header-img-container">
								<a href="/"><img src="../images/logos/hummingbird.png" width="74" height="100" alt="Hummingbird"/></a>
						</div>
				</div>
				<div id="page-content">
						<div id="description">
								<p>You're almost done!</p>
								<p id="message">
										To make sure you're not trying to sign up your mom, we need to verify your email address. You should be recieving an e-mail from us shortly with further instructions.
										Until then, checkout how Hummingbird works or download our awesome Chrome extension! If you haven't done so already you'll need to in a bit anyway, so might as well do it now.
								</p>
						</div>
						<div id="works-master-container">
								<div id="works-container">
										<div id="prev" class="works-child uiWorksNavigationControls">
												<img src="../images/br_prev.png" width="48" height="48" alt="prev"/>
										</div>
										<div id="works-content-wrapper" class="works-child">
												<div id="0" class="element current">
														<div class="step-holder">
																<img src="../images/steps/step0.png" width="800" height="350" alt="How it works"/>
														</div>
												</div>
												<div id="1" class="element">
														<div class="step-holder">
																<img src="../images/steps/step1.png" width="800" height="350" alt="step1"/>
														</div>
												</div>
												<div id="2" class="element">
														<div class="step-holder">
																<a href='https://chrome.google.com/webstore/detail/adbpoijidlpgogcfalangpplfoigeomn'><img src="../images/steps/step2.png" width="800" height="350" alt="step2"/></a>
														</div>
												</div>
												<div id="3" class="element">
														<div class="step-holder">
																<img src="../images/steps/step3.png" width="800" height="350" alt="step3"/>
														</div>
												</div>
												<div id="4" class="element">
														<div class="step-holder">
																<img src="../images/steps/step4.png" width="800" height="350" alt="step4"/>
														</div>
												</div>
												<div class="clear"></div>
										</div>
										<div id="next" class="works-child uiWorksNavigationControls">
												<img src="../images/br_next.png" width="48" height="48" alt="next"/>
										</div>
										<div class="clear"></div>
								</div>
						</div>
						<div class="clear"></div>
				</div>
		</body>
</html>