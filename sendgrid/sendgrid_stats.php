<?php
	include_once('config.php');
	require_once('class/sendgrid.php');
	$sendgrid     = new Sendgrid(SENDGRID_USER , SENDGRID_PASS,"",SENDGRID_API_URL);
	// Subtracting months from a date
	$date = date( "Y-d-m" );
	$start_date = date('Y-m-d', strtotime ( '-1 month' , strtotime ( $date ) ) ;	
	$end_date   = $date;

	$response   = $sendgrid->getSendGridStats( $start_date, $end_date );
    
    //response contains data in json string which has stats for each date
    if($response){
		$results = json_decode($response,true);
		// adding each date count to generate total counts for given time interval. 
		$total_result_arr = array();
		foreach( $results as $result ){
			foreach ($result as $key => $value) {
			   if(!isset($total_result_arr[$key]))
			   	$total_result_arr[$key] = 0;
			   $total_result_arr[$key] = $total_result_arr[$key] + $value;
			}
		}

		/*Printing stats from sendgrid in array format for a given time period */
		echo "<pre>";
		print_r($total_result_arr);
    } else {
    	echo "Some error occured while getting stats.";
    }

