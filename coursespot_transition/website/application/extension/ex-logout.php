<?php
		
		session_set_cookie_params(0, '/', '.coursespot.net');
		session_start();
		
		// Destroy Session
		session_destroy();
		
		// Destroy Cookie
		setcookie("user_email",NULL,time()-2592000,'/');
		
		
		echo "1";
		exit(0);
?>
