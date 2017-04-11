<?php
  include_once('config.php');
  require_once('class/sendgrid.php');
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
  $files        = array('attachement1.txt','attachement2.txt'); // Attachements should uploaded in uploads folder.

  /* Call for send mail function of sendgrid class */
  $result       = $sendgrid->sendGridMail( $to, $from, $from_name, $cc, $bcc, $reply_to, $subject, $html_message, $text_message, $files ); 
  if($result){
    echo "Email sent successfully.";
  } else {
     echo "Failed to send email."; 
  }
