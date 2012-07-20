<?php
		require_once("application/global/includes/gbFunctions.php");
		
		if(loggedIn()){
				header( 'Location: application/main/' );
				exit(0);
		}
?>


<!DOCTYPE html>
<html>
		<head>
				<meta charset="UTF-8"/>
				<title>Hummingbird</title>
				<link href='http://fonts.googleapis.com/css?family=Cantarell:400,700' rel='stylesheet' type='text/css'>
				<link href='application/fancybox/jquery.fancybox-1.3.4.css' rel='stylesheet' type='text/css'/>
				<link rel="stylesheet" type="text/css" href="css/index.css" media="screen" />
				<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
				<script src='application/fancybox/jquery.fancybox-1.3.4.pack.js' type='text/javascript'></script>
				<script src="js/index-min.js" type='text/javascript'></script>
				<script src='js/g-analytics.js' type='text/javascript'></script>
		</head>
		<body>
				<div id="header">
						<div id="header-img-container">
								<a href="/"><img src="images/logos/hummingbird.png" width="74" height="100" alt="logo"/></a>
						</div>
				</div>
				<div id="page-content">
						<div id="main-content-container">
								<div id="logo-container">
										<div id="hummingbird-holder">
												<span id="humming">HUMMING</span><span id="bird">BIRD</span>
										</div>
										<div id='ui-elements'>
												<div id='ui-navigation-holder'>
														<ul id='ui-navigation-menu'>
																<li class='ui-navigation-element'><a class='ui-menu-link ui-menu-scrollable' href='#about'>About</a></li>
																<li class='ui-navigation-element'><a class='ui-menu-link ui-menu-scrollable' href='#reg'>Register</a></li>
																<li class='ui-navigation-element'><a class='ui-menu-link ui-menu-scrollable' href='#contact'>Contact</a></li>
																<li class='ui-navigation-element'>
																		<a class='ui-menu-link' href='#login' id='login-link'>Login</a>
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
																</li>
														</ul>
												</div>
												<div class='clear'></div>
										</div>
								</div>
								<div id="down-arrow"></div>
								<div id="about-container">
										<p id="about-description"><span class="black">Humming</span><span class="red">bird</span> is a USC course scheduling assistant designed with one goal, take the courses you want, when you want them.
										Its like having your own personal secretary. Hummingbird continuously monitors the classes that you pick and as soon as they become available, guess whose the first to know?
										For those of you who have heard enough and want to get started, sign-up below. For the rest of you skeptics, keep reading to find out how Hummingbird works!</p>
								</div>
								<div class="content-divider"></div>
								<div id="registration-master-container">
										<div id="register-wrapper">
												<div id="registration-title">sign-up</div>
												<form id="registration-form" action="/application/register/register.php" method="post" autocomplete='off'>
														<div class="form-element">
																<div class="form-label">First Name<span class="required">*</span></div>
																<input id="first-name" class="form-input" name="first-name" type="text" required="required"/>
														</div>
														<div class="form-element">
																<div class="form-label">Last Name<span class="required">*</span></div>
																<input id="last-name" class="form-input" name="last-name" type="text" required="required"/>
														</div>
														<div class="form-element">
																<div class="form-label">e-mail<span class="required">*</span></div>
																<input id="e-mail" class="form-input" name="e-mail" type="email" required="required"/>
														</div>
														<div class="form-element">
																<div class="form-label">password<span class="required">*</span></div>
																<input id="pwd" class="form-input" name="pwd" type="password" required="required"/>
																<p class="reg-err-msg" id="pwd-error">Password must be at least 6 characters long</p>
														</div>
														<div class="form-element">
																<div class="form-label">confirm password<span class="required">*</span></div>
																<input id="confirm-pwd" class="form-input" type="password" required="required"/>
																<p class="reg-err-msg" id="confirm-pwd-error">These passwords don't match. Try Again?</p>
														</div>
														<div class="form-element">
																<input class="form-input" type="submit" value="Register"/>
														</div>
												</form>
										</div>
										<div id="register-divider"></div>
										<div id="register-description">
												<ul id="rdescription-items">
														<li>It only takes a few seconds.</li>
														<li>It's completely free.</li>
														<li>We never want you to feel like this guy. That's why we promise to never spam you.</li>
												</ul>
												<img id="spam-guy" src="images/spam-guy.png" width="185" height="216" alt="spam-guy"/>
										</div>
										<div class="clear"></div>
								</div>
								<div class="content-divider"></div>
								<div id="works-master-container">
										<div id="works-container">
												<div id="prev" class="works-child uiWorksNavigationControls">
														<img src="images/br_prev.png" width="48" height="48" alt="prev"/>
												</div>
												<div id="works-content-wrapper" class="works-child">
														<div id="0" class="element current">
																<div class="step-holder">
																		<img src="images/steps/step0.png" width="800" height="350" alt="step0"/>
																</div>
														</div>
														<div id="1" class="element">
																<div class="step-holder">
																		<img src="images/steps/step1.png" width="800" height="350" alt="step1"/>
																</div>
														</div>
														<div id="2" class="element">
																<div class="step-holder">
																		<a href='https://chrome.google.com/webstore/detail/adbpoijidlpgogcfalangpplfoigeomn'><img src="images/steps/step2.png" width="800" height="350" alt="step2"/></a>
																</div>
														</div>
														<div id="3" class="element">
																<div class="step-holder">
																		<img src="images/steps/step3.png" width="800" height="350" alt="step3"/>
																</div>
														</div>
														<div id="4" class="element">
																<div class="step-holder">
																		<img src="images/steps/step4.png" width="800" height="350" alt="step4"/>
																</div>
														</div>
														<div class="clear"></div>
												</div>
												<div id="next" class="works-child uiWorksNavigationControls">
														<img src="images/br_next.png" width="48" height="48" alt="next"/>
												</div>
												<div class="clear"></div>
										</div>
								</div>
								<div class="content-divider"></div>
								<div id="faq-container">
										<p id="faq-header">THINGS TO REMEMBER</p>
										<ul id="faq-elements">
												<li>Hummingbird is currently designed to only work with USC's schedule of classes. If you don't go to USC, try it out anyway. After you fall in love with it get in touch with us and we'll see if we can bring Hummingbird to your school!</li>
												<li>Remember before Hummingbird when you used to check course availability every 5 minutes? Yeah..., Those guys are still out there. So while Hummingbird can give you a heads up on when a class is open, you still have to go register for it.</li>
												<li>If you're having any trouble, get in touch with us. We'll get you up and running in no time.</li>
										</ul>
								</div>
								<div class="content-divider"></div>
								<div id="contact-container">
										<p id="contact-title">SAY HELLO</p>
										<p id="contact-header">We would love to hear from you. Tell us if something is broken, a feature you'd like to see, any trouble you're have using Hummingbird, or if you would just like to say Hi. If you love Hummingbird, spread the word by telling all your friends about how awesome we are.</p>
										<div id="form-container">
												<form id="contact-form">
														<div class="contact-form-element-container">
																<div class="form-element">
																		<div class="form-label">full name</div>
																		<input id="form-name" class="form-input" type="text" required="required"/>
																</div>
																<div class="form-element">
																		<div class="form-label">e-mail</div>
																		<input id="form-email" class="form-input" type="email" required="required"/>
																</div>
																<div class="form-element">
																		<input class="form-input" type="submit" value="Submit"/>
																</div>
														</div>
														<div class="contact-form-element-container">
																<div id="contact-text" class="form-element contact">
																		<div class="form-label">your thoughts</div>
																		<textarea id="form-textarea" rows="10" cols="55" required="required"></textarea>
																</div>
														</div>
														<input id="type" type="hidden" value="contact"/>
														<div class="clear"></div>
												</form>
										</div>
								</div>
						</div>
						<div class="clear"></div>
				</div>
				<div id="footer"></div>
		</body>
</html>