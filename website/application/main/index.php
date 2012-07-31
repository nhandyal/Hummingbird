<?php
		require_once("../global/includes/db-open.php");
		require_once("../global/includes/gbFunctions.php");
				
		if(!loggedIn()){
				header( 'Location: /' );
				exit(0);
		}
		
		$query = "SELECT first_name, last_name, first_login FROM users WHERE email='".userEmail()."'";
		$result = mysqli_query($link,$query);
		$userR = mysqli_fetch_assoc($result);
		$name = strtoupper($userR['first_name'])." ".strtoupper($userR['last_name']);
?>

<!DOCTYPE html>
<html>
		<head>
				<title><?php echo $name; ?> | Hummingbird</title>
				<link href='http://fonts.googleapis.com/css?family=Cantarell:400,700' rel='stylesheet' type='text/css'/>
				<link href='../fancybox/jquery.fancybox-1.3.4.css' rel='stylesheet' type='text/css'/>
				<link href='../css/main/index.css' rel='stylesheet' type='text/css'/>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
				<script src='../fancybox/jquery.fancybox-1.3.4.pack.js' type='text/javascript'></script>
				<script src='../js/main/index-min.js' type='text/javascript'></script>
				<script src='../js/g-analytics.js' type='text/javascript'></script>
		</head>
		<?php
				$query = "SELECT a.status, b.* FROM user_courses a LEFT JOIN courses b ON a.term=b.term AND a.deptAbbreviation=b.deptAbbreviation AND a.sectionNumber=b.sectionNumber WHERE a.email='".userEmail()."'";
				$result = mysqli_query($link,$query);
		?>
		<body>
				<?php
						if(mysqli_num_rows($result) != 0){
								echo "<div id='black-overlay'></div>";
						}
				?>
				<div id="settings">
						<ul>
								<li><a href="settings.php">Settings</a></li>
								<li><a href="../../help.html">Help</a></li>
								<!--<li><a href="">Contact</a></li>-->
								<li id='settings-menu-divider'></li>
								<li><a href="logout.php">Logout</a></li>
						</ul>
				</div>
				<div id="header-container">
						<div id='header' class='unselectable'>
								<span id='header-title'>HUMMING<span class='red'>BIRD</span></span>
								<div id='account-settings'>
										<a href='/' style='text-decoration:none'><span id='account-name'><?php echo $name; ?></span></a>
										<div id='settings-toggle'></div>
								</div>
						</div>
						<div class='clear'></div>
				</div>
				<div id='master-wrapper' class='unselectable'>
						<div id='add-class-container'>
								<div id='select-term-container' class='unselectable'>
										<span id='selected-term'>Select Term</span>
										<span class='down-arrow'></span>
										<input type='hidden' id='selected-term-number'/>
								</div>
								<a id='close-add-container' href='/'><div class='ui-x-icon'></div></a>
								<div id='term-container'>
										<ul class='ui-drop-menu' id='ui-term-select'>
												<?php
														$servResponse = curlURL("http://web-app.usc.edu/ws/soc/api/terms");
														$jsonResponse = json_decode($servResponse);
														foreach($jsonResponse->term as $term){
																$semester = "";
																switch(intval(substr($term,-1))){
																		case 1:
																				$semester = "SPRING ";
																				break;
																		case 2:
																				$semester = "SUMMER ";
																				break;
																		case 3:
																				$semester = "FALL ";
																				break;
																}
																$semester .= substr($term,0,-1);
																echo "<li id='$term'>$semester</li>";
														}
												?>
										</ul>
								</div>
								<div class='select-container' id='select-department-container'>
										<div class='search-container'  id='department-search-container'>
												<span class='search-icon'></span>
												<input type='text' class='searchbox' id='department-search' placeholder='Search Departments' readonly='readonly'/>
												<input type='hidden' id='department-search-preVal'/>
												<input type='hidden' id='selected-department'/>
										</div>
										<div class='data-container' id='department-data-container'></div>
								</div>
								<div class='select-container' id='select-course-container'>
										<div class='search-container'  id='course-search-container'>
												<span class='search-icon'></span>
												<input type='text' class='searchbox' id='course-search' placeholder='Search Courses' readonly='readonly'/>
												<input type='hidden' id='course-search-preVal'/>
										</div>
										<div class='data-container' id='course-data-container'></div>
								</div>
								<div class='select-container' id='select-section-container'>
										<div id='section-header'>Available Sections</div>
										<div id='section-data-header-container'>
												<div class='section-data-header section-data-content'>Section</div>
												<div class='section-data-header section-data-content'>Type</div>
												<div class='section-data-header section-data-content'>Time</div>
												<div class='section-data-header section-data-content'>Days</div>
												<div class='section-data-header section-data-content'>Instructor</div>
										</div>
										<div class='data-container' id='section-data-container'></div>
								</div>
								<div id='hidden-section-data'></div>
								<div id='hidden-loading-gif' style='display:none'>
										<img src='../images/loading.gif' width='50' height='50' style='margin-top:50px; margin-left:100px; opacity:0.3'/>
								</div>
						</div>
						<?php
								if(mysqli_num_rows($result) == 0){
						?>
										<div id='welcome-wrapper' class='unselectable'>
												<?php
														if($userR['first_login'] == 1){
																// user's first login, load help data
																echo "<div id='first-login'></div>";
												?>
																<script type='text/javascript'>
																		$.get("first-login.txt",{},function(response){
																				$('#first-login').html(response);
																				$('#welcome-wrapper').css({"left":"0px"});
																		});
																</script>
												<?php
														}
														else{
																echo "<div id='welcome-arrow' class='unselectable'></div>";
																echo "<div id='welcome-caption' class='text-selectable'>Hey there and welcome back to Hummingbird! You currently don't have any classes on your watch-list. Add some more classes by using the built in course browser or by using our awesome <a href='https://chrome.google.com/webstore/detail/adbpoijidlpgogcfalangpplfoigeomn' style='color:inherit'>Google Chrome Extension</a>.</div>";
																echo "<div id='welcome-guy' class='unselectable'></div>";
																echo "<div id='painting-guy' class='unselectable'></div>";
														}
												?>
										</div>
										<div id='hummingbird-logo-container' class='unselectable'><div id='hummingbird-logo'>HUMMINGBIRD</div></div>
						<?php
								}
								else{
						?>
								<div id='main-content-wrapper'>
										<div id='table-headers'>
												<table class='course-data-table text-selectable'>
														<tr class='course-data-table-header'>
																<td class='status-data'>Status</>
																<td class='type-data'>Type</td>
																<td class='instructor-data'>Instructor</td>
																<td class='courseDays-data'>Course Days</td>
																<td class='time-data'>Time</td>
																<td class='term-data'>Term</td>
																<td class='location-data'>Location</td>
																<td class='options-data'>Options</td>
														</tr>
												</table>
										</div>
										<div id='course-dataTable-container'>
												<div id='ui-scroll-controls'>
														<div class='ui-scroll-control-element' onclick="scrollDown()">
																<div id='ui-scrollable-up'></div>
														</div>
														<div class='ui-scroll-control-element' onclick="scrollUp()">
																<div id='ui-scrollable-down'></div>
														</div>
												</div>
												<div id='course-slider-container'>
														<?php
																$r = mysqli_fetch_assoc($result);
																$courseDelimiter = strpos($r['publishedCourseName'],':');
																$courseName = substr($r['publishedCourseName'],0,$courseDelimiter);
																$courseDescription = substr($r['publishedCourseName'],$courseDelimiter+1);
																echo "<div class='course-tile scroll-first'>";
																echo "<div class='course-tile-content text-selectable'>";
																echo "<p class='course-header'>".$courseName."</p>";
																echo "<p class='course-description'>".$r['sectionNumber'].":".$courseDescription."</p>";
																echo "<input class='course-data-id' type='hidden' value='".$r['deptAbbreviation']."-".$r['sectionNumber']."'/>";
																echo "</div>";
																echo "</div>";
																while($r = mysqli_fetch_assoc($result)){
																		$courseDelimiter = strpos($r['publishedCourseName'],':');
																		$courseName = substr($r['publishedCourseName'],0,$courseDelimiter);
																		$courseDescription = substr($r['publishedCourseName'],$courseDelimiter+1);
																		echo "<div class='course-tile'>";
																		echo "<div class='course-tile-content'>";
																		echo "<p class='course-header'>".$courseName."</p>";
																		echo "<p class='course-description'>".$r['sectionNumber'].":".$courseDescription."</p>";
																		echo "<input class='course-data-id' type='hidden' value='".$r['deptAbbreviation']."-".$r['sectionNumber']."'/>";
																		echo "</div>";
																		echo "</div>";
																}
														?>
												</div>
												<div id='transition-elements-container'>
														<div id='transition-element-0' class='transition-elements-subContainer'>
																<div class='transition-element-up'></div>
														</div>
														<div id='transition-element-1' class='transition-elements-subContainer'>
																<div class='transition-element-up'></div>
														</div>
														<div id='transition-element-2' class='transition-elements-subContainer'>
																<div class='transition-element-up'></div>
														</div>
														<div id='transition-element-3' class='transition-elements-subContainer'>
																<div class='transition-element-up'></div>
														</div>
												</div>
												<div id='course-content-container'>
														<div id='course-content-0' class='course-content-tile'><div class='course-content'></div></div>
														<div id='course-content-1' class='course-content-tile'><div class='course-content'></div></div>
														<div id='course-content-2' class='course-content-tile'><div class='course-content'></div></div>
														<div id='course-content-3' class='course-content-tile'><div class='course-content'></div></div>
												</div>
												<div class='clear'></div>
										</div>
								</div>
								<div id='course-data'>
										<?php
												mysqli_data_seek($result,0);
												while($r = mysqli_fetch_assoc($result)){
														$courseID = $r['deptAbbreviation']."-".$r['sectionNumber'];
										?>
														<div id='<?php echo $courseID; ?>'>
																<table class='course-data-table text-selectable'>
																		<tr class='course-data-table-content' valign='top'>
																				<td class='status-data'>
																						<?php
																								switch(intval($r['status'])){
																										case 0:
																												echo "<div class='status-icon icon-active'></div> Active";
																												break;
																										case 1:
																												echo "<div class='status-icon icon-inactive'></div> Inactive";
																												break;
																								}
																						?>
																				</td>
																				<td class='type-data'><?php echo $r['courseType']; ?></td>
																				<td class='instructor-data'>
																						<?php
																								if($r['instructor'] == '')
																										echo " -- ";
																								else
																										echo $r['instructor'];
																						?>
																				</td>
																				<td class='courseDays-data'>
																						<?php
																								$days = $r['courseDays'];
																								$replacmentCharacters = array('[',']','"');
																								$days = str_replace($replacmentCharacters,"",$days);
																								$days = explode(",",$days);
																								foreach($days as $day){
																										echo $day."<br/>";
																								}
																						?>
																				</td>
																				<td class='time-data'><?php echo $r['startTime']." - ".$r['endTime']; ?></td>
																				<td class='term-data'>
																						<?php
																								switch (substr($r['term'],-1)){
																										case '1':
																												echo "Spring ".substr($r['term'],0,-1);
																												break;
																										case '2':
																												echo "Summer ".substr($r['term'],0,-1);
																												break;
																										case '3':
																												echo "Fall ".substr($r['term'],0,-1);
																												break;
																								}
																						?>
																				</td>
																				<td class='location-data'>
																						<?php
																								$location = $r['location'];
																								$building = substr($location,0,3);
																								$room = substr($location,3);
																								echo $building."-".$room;
																						?>
																				</td>
																				<td class='options-data'>
																						<?php $removeCourseData = "('".userEmail()."','".$r['term']."','".$r['deptAbbreviation']."','".$r['sectionNumber']."','".$r['courseType']."')"; ?>
																						<a href="javascript:confirmDeleteCourse<?php echo $removeCourseData; ?>"><div class='ui-x-icon'></div></a>
																				</td>
																		</tr>
																</table>
														</div>
								<?php
												}
										}
								?>
								<div class='clear'></div>
				</div> <!--End Master Wrapper-->
		</body>
</html>
