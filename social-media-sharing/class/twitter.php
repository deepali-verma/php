<?php
  include('twitter/autoload.php');
  use Abraham\TwitterOAuth\TwitterOAuth;
   
  class Twitter {
    var $twitter;
    public function Twitter( $config ){
     
      $consumerKey      = $config['consumerKey'];
      $consumerSecret   = $config['consumerSecret'];
      $oauthToken       = $config['oauthToken'];
      $oauthTokenSecret = $config['oauthTokenSecret'];
      $this->twitter = new TwitterOAuth($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret);
      //$this->twitter = new TwitterOAuth('3WE0Tm4dKBzWpyt43nEeoFAcu', 'x44jA11YBC16WZHruAc15JHU7UgaK2z9v2l3ANvkqhpVfuInn1', '884545507-Q1rju5XrMxxlRqBhi3hGidvcxJvBpNJhct10Qmdj', 'NrYAWorEIUcmDqiqdsLUpdmMGdv7rA0pdBn1ANQoAP8XP');
    
    }  
  
    
        public function postStatusesUpdateWithMedia( $imageUrl, $propertyDetailsUrl, $propertyDetails )
        {
            
            $this->twitter->setTimeouts(60, 30);
            $file_path = $imageUrl;
            $result = $this->twitter->upload('media/upload', array('media' => $file_path));   
            $message = "$propertyDetails";
            if( isset( $result->media_id_string ) ){
              $parameters = array( 'status' => $message, 'media_ids' => $result->media_id_string );
              $result = $this->twitter->post('statuses/update', $parameters); 
              if( isset($result->id_str) )
                return $post_id = $result->id_str;
              else
                return "";
            } else {
                return "";
            }     
        }
  }
?>