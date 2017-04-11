<?php
define(PAYPAL_URL_TEST,'https://www.sandbox.paypal.com/cgi-bin/webscr'); //Test Paypal API URL,for test use
define(PAYPAL_URL_LIVE,'https://www.paypal.com/cgi-bin/webscr'); //For live use
define('mode', 'live'); // live/test
define(PAYPAL_ID,""); // Business email ID
require_once 'db_config.php';
?>