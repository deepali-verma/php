## Social Media Sharing 
        Sharing of site contents on social meadia Facebook,Linkedin,Twitter with one time login 
        and storing access token.

### Share on Facebook
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
    
### Share on Twitter
    $config = array(
        'consumerKey' => TWITTER_CONSUMER_KEY,
        'consumerSecret' => TWITTER_CONSUMER_SECRET,
        'oauthToken' => TWITTER_ACCESS_TOKEN,
        'oauthTokenSecret' => TWITTER_ACCESS_SECRET
    );

    $message              = "What to share"; // message to share max 140 characters.
    $link                 = "link to be share"; // sharing link
    $picture              = "picture url to be share";  // picture url to be share

    $twitter = new Twitter( $config );
    $post_id = $twitter->postStatusesUpdateWithMedia($picture, $link, $message);

### Share on Linkedin

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
### Dependecies
        PHP 5.5

### Social Media configurations - include/config.php
    Create an app on social media using developer account and replace following config constants
    /* Facebook */
    define( 'FACEBOOK_APP_ID', '');
    define( 'FACEBOOK_APP_SECRET', '');
    define( 'FACEBOOK_RETURN_URL', '../facebook_share.php');
    /* Linkedin */
    define( 'LINKEDIN_APP_KEY', '' );
    define( 'LINKEDIN_SECRET_KEY', '');
    define( 'LINKEDIN_ACCESS_TOKEN', '');
    define( 'LINKEDIN_CALLBACK_URL', '../linkedin_share.php');
    /* Twitter */
    define( 'TWITTER_CONSUMER_KEY', "" );
    define( 'TWITTER_CONSUMER_SECRET', "");
    define( 'TWITTER_ACCESS_TOKEN', "");
    define( 'TWITTER_ACCESS_SECRET', "");

## API Reference
    1. Facebook PHP sdk from Facebook.
    2. Twitter PHP sdk from Twitter.


