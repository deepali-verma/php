<?php
	//Checking whether session exist or not
	if (session_status() == PHP_SESSION_NONE) {
		//Sarting session
	    session_start();
	}
	//Checking whether user data exist in session or not, to unset session data
	if(isset($_SESSION['user_name'])){
	    session_destroy();
            $_SESSION['user_name'] = NULL;
            unset($_SESSION['user_name']);
	    //Redirecting to login page if user is logged out
	    header("Location:login.php");
	}