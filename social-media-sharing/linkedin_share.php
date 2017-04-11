<?php

    include_once('include/config.php');
    require_once('class/linkedin.php');
    /* Setting config array */
    $config = array(
        'client_id'     => LINKEDIN_APP_KEY,
        'client_secret' => LINKEDIN_SECRET_KEY,
        'access_token'  => LINKEDIN_ACCESS_TOKEN,
        'redirect_url'  => LINKEDIN_CALLBACK_URL
    );

    /* creating object of LINKEDIN Class */
    $linkedin = new LINKEDIN( $config );

    /* var intialization */
    $accessToken = "";

    /* Checking if user is already authenticated by Linkedin and access token saved in session */
    if(isset( $_SESSION['accessToken'] ))
        $accessToken = $_SESSION['accessToken'];

    /* Checking code retrun from Linked in as callback to get access token */
    if (isset($_GET['code'])) {
        $code        = $_GET['code'];
        /* Getting access token */
        $accessToken = $this->linkedin->getAccessToken($code);
        /* Storing access token in session for next time sharing */
        $_SESSION['accessToken'] = $accessToken;
    } else if (isset($_GET['error'])) {
        echo $_GET['error'];
    }

    /* If user is not authenticated by Linkedin then send on Linkedin for user authentication */
    if (!$accessToken) {
        $this->linkedin->login();
        exit;
    } else {
        $message = ""; // Message to share
        $link    = ""; // Link to share
        $picture = ""; // Picture url to share        
        /*Sharing post*/
        $post_id = $this->linkedin->sharePost("", "", $message, $link, $picture);
    }