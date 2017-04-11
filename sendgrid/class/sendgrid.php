<?php
  /**********************************************************************************************************
   * Sendgrid class has methods to makes calls to sendgrid web api end points.                              *
   * Includes functions to send email  and get Sendgrid stats.                                              *
   * Includes function to execute curl request.                                                             *
   **********************************************************************************************************/ 
  class Sendgrid {
    private $user    = "";
    private $pass    = "";
    private $method  = "";
    private $url     = "";

    /* Defining constructor for class Sendgrid */
    public function Sendgrid( $user, $pass, $method="" $apiUrl){      
      $this->user     = $user;
      $this->pass     = $pass;
      $this->method   = $method;
      $this->url      = $apiUrl;     
    }

   /**   
    * @access public
    * @param array $toMail array of reciepents emails
    * @param str $fromMail sender email
    * @param str $fromName sender name 
    * @param array $CCMail array of cc reciepents emails
    * @param array $BCCMail array of bcc reciepents emails  
    * @param str $subject email subject   
    * @param str $HTML_Body eamil html body content
    * @param str $TextBody  eamil text body content
    * @param array $Attach_File_List array of all attached files in email  
    * @return boolean is_mail_sent
    **/
    public function sendGridMail($toMail_arr, $fromMail, $fromName, $ccMail_arr, $bccMail_arr, $replyTo, $subject, $HTML_Body, $TextBody, $Attach_File_List)
    {
      
      $HTML_Body = utf8_encode($HTML_Body);      
      $is_mail_sent = false;      
      $params = array(
        'api_user'  => $this->user,
        'api_key'   => $this->pass,
        'replyto'   => $replyTo,
        'subject'   => $subject,
        'html'      => $HTML_Body,
        'text'      => $TextBody,
        'from'      => $fromMail,
        'fromname'  => $fromName,
      );

      foreach($ccMail_arr as $cc) {
          $toMail_arr = array_merge($toMail_arr, $cc);
      }

      for($i=0; $i<count($toMail_arr); $i++)
      {
        if($toMail_arr[$i]!="")
          $params['to['.$i.']'] = $toMail_arr[$i];
      }
       
      if($TextBody!="")
      {
        $params['text'] = $TextBody;
      }
      for($b=0; $b<count($bccMail_arr); $b++)
      {
        if($bccMail_arr[$b]!="")
          $params['bcc['.$b.']'] = $bccMail_arr[$b];
      }      
      
      $Attach_File_Lists = $Attach_File_List; 
      foreach( $Attach_File_Lists as $Attach_File_List )
      {
        $fileName = $Attach_File_List['name'];        
        if(file_exists(MainUploadFolderPath.$fileName) && MainUploadFolderPath.$fileName!="")
        {
          $params['files['.$fileName.']'] = '@'.MainUploadFolderPath.$fileName;          
        }
      }
    
      $request  =  $this->url.'api/mail.send.json';
      $response = $this->executeCurl( $request, $params );

      if($response!="")
      {
        $Obj = json_decode($response);        
        if($Obj->{'message'}=="success")
          $is_mail_sent = true;
        else{
          /* printing error */
          echo "<pre>";
          print_r( $response );
          $is_mail_sent = false;
        }
      }
      else
        $is_mail_sent = false;
      return $is_mail_sent;
    }

    /**
     * @access public       
     * @param str $start_date
     * @param str $end_date
     * @return str $response json string stats response
    **/    
    public function getSendGridStats ( $start_date, $end_date ){
      
      $request =  $this->url.'api/stats.get.json';
      $params = array(
        'api_user'   => $this->user,
        'api_key'    => $this->pass,
        'start_date' => $start_date,
        'end_date'   => $end_date,
        'data_type'  => 'global',
        'metric'     => 'all'
      );  

      return $this->executeCurl( $request, $params );  
    }


    /**
     * @access private       
     * @param str $request uri request url
     * @param str $params  curl request parameters
     * @return str $response curl execution result 
    **/    
    private function executeCurl ($request,$params){     
      // Generate curl request
      $session = curl_init($request);
      // Tell curl to use HTTP POST
      curl_setopt ($session, CURLOPT_POST, true);
      //curl_setopt($session, CURLOPT_SAFE_UPLOAD, true);
      // Tell curl that this is the body of the POST
      curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
      // Tell curl not to return headers, but do return the response      
      curl_setopt($session, CURLOPT_HEADER, false);      
      curl_setopt($session, CURLOPT_ENCODING, 'gzip,deflate' );
      curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
      // obtain response
      $response = curl_exec($session);
      //print_r(expression)
      curl_close($session);
      return $response;
    }

  }
?>
