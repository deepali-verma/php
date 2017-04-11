<?php

    include_once('include/config.php');
    require_once('class/facebook.php');
    /* Setting config array */
    $config['appId']     = FACEBOOK_APP_ID;
    $config['appSecret'] = FACEBOOK_APP_SECRET;
    $config['returnUrl'] = FACEBOOK_RETURN_URL;

    /* creating object of Facebook Class */
	$facebook    = new Facebook( $config ); 

    /* var intialization */
    $accessToken = "";

    /* Checking if user is already authenticated by Facebook and access token saved in session */
    if(isset($_SESSION['accessToken'])){
        $accessToken = $_SESSION['accessToken'];
    }
    /* Getting access token if it returns from facebook as callback */
    if($accessToken == "" ){
        /* Getting access token */
        $accessToken = $this->facebook->getAccessToken();
        /* Storing access token in session for next time sharing */
        $_SESSION['accessToken'] = $accessToken;
    }
    
    if ($accessToken) {
        $message              = "What to share"; // Message to share
        $link                 = "link to be share"; // Link to share
        $picture              = "picture url to be share";	// Picture url to share
        /*Sharing post on facebook */
        $post_id = $this->facebook->sharePost( $accessToken, $link, $message, $picture );
    } else {
        /* Sending user on facebook for authentication */
        redirect($this->facebook->loginUrl());
    }