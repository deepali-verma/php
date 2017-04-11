<?php
    require_once('facebook/autoload.php');
    use Facebook\FacebookSession;
    use Facebook\FacebookRequest;
    use Facebook\GraphUser;
    use Facebook\FacebookRequestException;
    use Facebook\FacebookRedirectLoginHelper;
    use Facebook\Entities\AccessToken;
    use Facebook\FacebookSDKException;
    
    
    class Facebook {
      var $helper;
      var $session;
      var $returnUrl = "";
      
      public function Facebook( $config ) {
      
        $appId     = $config['appId'];
        $appSecret = $config['appSecret'];
        $returnUrl = $config['returnUrl'];
      
        // init app with app id (APPID) and secret (SECRET)
        //FacebookSession::setDefaultApplication('792810087462521','983f118e6a1b3670fae7946d1db351a7');
        FacebookSession::setDefaultApplication( $appId, $appSecret );
        $this->returnUrl = $returnUrl;  
      }
      
       public function loginUrl() {
          $this->helper = new FacebookRedirectLoginHelper( $this->returnUrl );
          $scope = array('publish_actions');
          return $this->helper->getLoginUrl($scope);
       }
       
      
      public function getAccessToken() {
        // login helper with redirect_uri
        $this->helper = new FacebookRedirectLoginHelper( $this->returnUrl );
        try {
        $this->session = $this->helper->getSessionFromRedirect();
        } catch(FacebookSDKException $e) {
          echo $e;
          exit;
            $this->session = null;
        }
        if ($this->session) {
          // User logged in, get the AccessToken entity.
          $accessToken = $this->session->getAccessToken();
          // Exchange the short-lived token for a long-lived token.
          $longLivedAccessToken = $accessToken->extend();
        } else {
            $scope = array('publish_actions');
            echo '<a href="' . $this->helper->getLoginUrl($scope) . '">Login with Facebook</a>';
        }
        
        if(isset($longLivedAccessToken))
        return $longLivedAccessToken; 
        
      }
      
      public function checkTokenValidity( $longLivedAccessToken ){
      
          $accessToken = new AccessToken($longLivedAccessToken);
          try {
            // Get info about the token
            // Returns a GraphSessionInfo object
            $accessTokenInfo = $accessToken->getInfo();
          } catch(FacebookSDKException $e) {
            echo 'Error getting access token info: ' . $e->getMessage();
            exit;
          }
          
          // Dump the info about the token
          $accessTokenInfo = $accessTokenInfo->asArray();
          if(isset( $accessTokenInfo['is_valid'] )) {
            $is_valid = $accessTokenInfo['is_valid'];
            return $accessToken;
          }
          return 0; 
      }
      
      public function sharePost( $longLivedAccessToken, $link, $message, $picture ) {
      
        try {
          // Get a code from a long-lived access token
          $code = AccessToken::getCodeFromAccessToken($longLivedAccessToken);
        } catch(FacebookSDKException $e) {
          echo 'Error getting code: ' . $e->getMessage();
          exit;

        }
        
        try {
          // Get a new long-lived access token from the code
          $newLongLivedAccessToken = AccessToken::getAccessTokenFromCode($code);
        } catch(FacebookSDKException $e) {
          echo 'Error getting a new long-lived access token: ' . $e->getMessage();
          exit;
        }
        
        // Make calls to Graph using $shortLivedAccessToken
        $this->session = new FacebookSession($newLongLivedAccessToken);
        
        try {
          $response = (new FacebookRequest(
            $this->session, 'POST', '/me/feed', array(
              'link' => $link, //'http://akanksha.salesipro.com/index.php/details/mls_id-718384-Residential',
              'message' => $message, //'Please check my new homes',
              'picture' => $picture//'http://166.62.43.121/~iproagents/ipro_mls/properties_photos/commercial/2015-01-27/image-default-721490-1.jpg'  
                
            )
          ))->execute()->getGraphObject();
      
          return $response->getProperty('id');
      
        } catch(FacebookRequestException $e) {
          
          return "";
      
        }
        return "";                               
        
     }
      
  }