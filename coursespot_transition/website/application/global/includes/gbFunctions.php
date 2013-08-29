<?php

		// start the session and set the path
		session_set_cookie_params(0, '/', '.coursespot.net');
		session_start();
		
		function loggedIn(){
				return (isset($_SESSION['user_email']) || isset($_COOKIE['user_email']));
		}
		
		function userEmail(){
				if(isset($_SESSION['user_email']))
						return $_SESSION['user_email'];
				else if(isset($_COOKIE['user_email']))
						return $_COOKIE['user_email'];
		}
		
		function curlURL($targetURL){
				// use cURL to term data
				$ch = curl_init( $targetURL );
				
				$options = array(
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_HTTPHEADER => array('Content-type: application/json'),
						CURLOPT_TIMEOUT => 5
				);
				
				curl_setopt_array( $ch, $options );
				
				// Getting results
				return curl_exec($ch); 
		}
?>