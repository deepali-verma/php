## Sendgrid - Send email and get email status using Sendgrid WEB API call.
Sendgrid module contains code in php to send email through sendgrid and get email stats from sendgrid using web api calls.

## Example - code samples

  	// Send email api call - Sendgrid class
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
      // Adding cc
      foreach($ccMail_arr as $cc) {
          $toMail_arr = array_merge($toMail_arr, $cc);
      }
      // Adding to
      for($i=0; $i<count($toMail_arr); $i++)
      {
        if($toMail_arr[$i]!="")
          $params['to['.$i.']'] = $toMail_arr[$i];
      }
       
      // Adding bcc
      for($b=0; $b<count($bccMail_arr); $b++)
      {
        if($bccMail_arr[$b]!="")
          $params['bcc['.$b.']'] = $bccMail_arr[$b];
      }      
      // Adding attachement list
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

      // get stats API call - Sendgrid class
      $request =  $this->url.'api/stats.get.json';
      $params = array(
        'api_user'   => $this->user,
        'api_key'    => $this->pass,
        'start_date' => $start_date,
        'end_date'   => $end_date,
        'data_type'  => 'global',
        'metric'     => 'all'
      ); 
## Installation
### Replace configs variables - config.php
	/* from email */
	define('FROM_EMAIL', '');
	/* from name */
	define('FROM_NAME', '');
	/* reply email */
	define('REPLY_TO', ''); 
	/* sendgrid user name */
	define('SENDGRID_USER', '');
	/* sendgrid user password */
	define('SENDGRID_PASS', '');
	/* sendgrid user password */
	define('SENDGRID_API_URL', 'https://api.sendgrid.com/');
	/*MainUploadFolderPath*/
	define('MainUploadFolderPath','/uploads');
	
### Send email - email.php
	  $sendgrid     = new Sendgrid(SENDGRID_USER , SENDGRID_PASS,"",SENDGRID_API_URL);
	
	  $to           = array("robert@gmail.com"); // add `,` separeted email addresses.
	  $from         = FROM_MAIL; // from mail, override if not want to use default in config.php
	  $from_name    = FROM_NAME; // from name, override if not want to use default in config.php
	  $cc           = array("james@gmail.com"); // add `,` separeted email addresses.
	  $bcc          = array("john@gmail.com"); // add `,` separeted email addresses.
	  $reply_to     = "john@gmail.com"; // Reply to email address.
	  $subject      = "Mail Subject Here"; // Email subject here.
	  $html_message = "<h1>HTML Message</h1>"; // Email html content leave blank if using only text content.
	  $text_message = ""; //  Email text content leave blank is using html content. 
	  $files        = array('attachement1.txt','attachement2.txt'); 
	
	  /* Call for send mail function of sendgrid class */
	  $result       = $sendgrid->sendGridMail( $to, $from, $from_name, $cc, $bcc, $reply_to, $subject, $html_message, $text_message, $files ); 


### Get Stats  - sendgrid_stats.php
   	$sendgrid     = new Sendgrid(SENDGRID_USER , SENDGRID_PASS,"",SENDGRID_API_URL);
	// Subtracting months from a date
	$date = date( "Y-d-m" );
	$start_date = date('Y-m-d', strtotime ( '-1 month' , strtotime ( $date ) ) ;	
	$end_date   = $date;

	$response   = $sendgrid->getSendGridStats( $start_date, $end_date );
