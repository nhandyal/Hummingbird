<?php
		require_once("../global/includes/db-open.php");
		require_once("../global/includes/gbFunctions.php");
				
		if(!loggedIn()){
				header( 'Location: /' );
				exit(0);
		}
		
		$query = "SELECT * FROM users WHERE email='".userEmail()."'";
		$result = mysqli_query($link,$query);
		$r = mysqli_fetch_assoc($result);
		$name = strtoupper($r['first_name'])." ".strtoupper($r['last_name']);
?>

<!DOCTYPE html>
<html>
		<head>
				<title>Acount Settings | Coursespot</title>
				<link href='http://fonts.googleapis.com/css?family=Cantarell:400,700' rel='stylesheet' type='text/css'>
				<link href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css' rel='stylesheet' type='text/css'/>
				<link href='../css/main/settings.css' rel='stylesheet' type='text/css'/>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
				<script src='../js/main/jquery.autotab-1.1b.js' type='text/javascript'></script>
				<script src='../js/main/settings-min.js' type='text/javascript'></script>
				<script src='../js/g-analytics.js' type='text/javascript'></script>
		</head>
		<body>
				<div id="settings">
						<ul>
								<li><a href="settings.php">Settings</a></li>
								<li><a href="logout.php">Logout</a></li>
								<li id='settings-menu-divider'></li>
								<li><a href="../../help.html">Help</a></li>
						</ul>
				</div>
				<div id='header-container'>
						<div id="header">
								<span id='header-title'>COURSE<span class='red'>SPOT</span></span>
								<div id='account-settings'>
										<a href='/' style='text-decoration:none'><span id='account-name'><?php echo $name; ?></span></a>
										<div id='settings-toggle'></div>
								</div>
								<div class='clear'></div>
						</div>
				</div>
				<div id='master-wrapper'>
						<div id='main-content'>
								<div id='settings-description'>SETTINGS</div>
								<div id='settings-main-content'>
										<div id='settings-static-elements'>
												<div class='static-element'>
														<span class='element-description'>Name:</span>
														<span class='static-element-content'><?php echo $name; ?></span>
												</div>
												<div class='static-element'>
														<span class='element-description'>E-Mail:</span>
														<span class='static-element-content'><?php echo userEmail(); ?></span>
												</div>
										</div>
										<div id='phone-content-wrapper'>
												<div id='phone-settings'>
														<?php
																if($r['vrf_phone'] == 1){
														
																		echo "<div class='static-element'>";
																		echo "<span class='element-description' style='float:left'>Mobile:</span>";
																    echo "<span class='static-element-content' style='float:left'>";
																		$phone_number = $r['phone_number'];
																		$expanded_phone_number = substr($phone_number,0,3)."-".substr($phone_number,3,3)."-".substr($phone_number,6,4);
																		echo $expanded_phone_number;
																		echo " on ".$r['carrier'];
																		echo "<br/>";
																		echo "<span style='position:relative; top:-10px;'><span id='remove-phone-icon'></span><span id='remove-phone-link'>Remove Mobile Phone</span></span></span>";
																		echo "<div class='clear'></div>";
																		echo "</div>";
																}
																else{
														?>
														<span class='element-description' style='float:left'>Mobile:</span>
														<div id='no-phone-message'>
																There is no phone registered with this account<br/>
																<span id='add-phone-link'>Add a mobile number</span>
														</div>
														<?php
																}
														?>
														<div id='add-phone-content'>
																<div id='add-mobile-container'>
																		<div class='add-phone-form-element'>
																				<div class='add-phone-form-description'>Mobile Number</div>
																				<div id='phone-number-input' class='add-phone-form-content'>
																						<input type='text' maxlength='3' id='areacode' style='width:40px'/> - <input type='text' maxlength='3' id='phoneNumber1' style='width:40px'/> - <input type='text' maxlength='4' id='phoneNumber2' style='width:60px'/>
																				</div>
																		</div>
																		<div class='add-phone-form-element'>
																				<div id='mobile-carrier-toggle'>
																						<span id='carrier-selected'>Mobile Carrier</span>
																						<span class='down-arrow'></span>
																						<div class='clear'></div>
																				</div>
																				<div id='mobile-carriers' class='drop-down-menu'>
																						<ul>
																								<li class='mobile-carrier'>AT&T</li>
																								<li class='mobile-carrier'>MetroPCS</li>
																								<li class='mobile-carrier'>Sprint</li>
																								<li class='mobile-carrier'>T-Mobile</li>
																								<li class='mobile-carrier'>U.S. Cellular</li>
																								<li class='mobile-carrier'>Verizon</li>
																				</div>
																				<div id='verify-mobile-container'>
																						<div id='verify-mobile' class='verify-mobile-button unclickable'>Verify Number</div>
																						<span class='superscript' style='color:rgb(60,60,60)'>*Standard messaging rates apply.</span>
																				</div>
																		</div>
																</div>
																<div id='verify-mobile-loading'></div>
																<div id='verify-mobile-code' class='add-phone-form-element'>
																		<div style='margin-bottom:7px'>
																				Enter the Coursespot mobile verification code in the space below. If you didn't receive a verification messages don't fret! Simply refresh the page and try again.
																		</div>
																		<input id='mobile-verification-code' type='text' maxlength='4' /><span id='verify-mobile-error'>Invalid verification code</span>
																		<div id='save-changes-addPhone' class='verify-mobile-button'>Save Changes</div>
																</div>
														</div>
												</div>
												<div class='clear'></div>
										</div>
										<div id='notification-settings-wrapper'>
												<div id='notification-settings-header'>Notification Settings</div>
												<div class='static-element'>
														<span class='element-description'>E-mail:</span>
														<span class='static-element-content'>Enabled</span>
												</div>
												<div class='static-element'>
														<span class='element-description'>Mobile:</span>
														<?php
																if($r['vrf_phone'] == 1){
																		//
																		if($r['text_notify'] == 1){
																				echo "<span class='static-element-content phone-notification'> Enabled <span class='down-arrow' style='margin-top:9px'></span></span>";
																		}
																		else{
																				echo "<span class='static-element-content phone-notification'> Disabled <span class='down-arrow' style='margin-top:9px'></span></span>";
																		}
																}
																else{
																		echo "<span class='static-element-content'> -- </span>";
																}
														?>
												</div>
												<div id='phone-notifications-menu' class='drop-down-menu'>
														<ul>
																<li class='phone-notification-status'>Enabled</li>
																<li class='phone-notification-status'>Disabled</li>
														</ul>
												</div>		
										</div>
										<?php
												if($r['vrf_phone'])
														echo "<div id='save-changes' class='verify-mobile-button'>Save Changes</div>"
										?>
										<div class='clear'></div>
								</div>
								<div class='clear'></div>
						</div>
				</div>
		</body>
</html>