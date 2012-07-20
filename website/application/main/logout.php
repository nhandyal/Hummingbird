<?php
		
		session_set_cookie_params(0, '/', '.hummingbirdapplication.com');
		session_start();
		
		// Destroy Session
		session_destroy();
		
		// Destroy Cookie
		setcookie("user_email",NULL,time()-2592000,'/');
		
		header( 'Location: /' );
		
		exit(0);
?>
