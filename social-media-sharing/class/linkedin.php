<?php



    /*

    * 

    * Class Name : Linkedin 

    * Created by : Anil Singh

    * Created at : 25/03/2015

    * Copy right : ©Ksolves

    *

    */



    class Linkedin {



        var $client_id     = "";

        var $client_secret = "";

        var $access_token  = "";

        var $redirect_url  = "";

        var $login_url     = "";

        var $debug         = true;



        // Constructure to class Linkedin

        public function Linkedin( $config ) {

            

            $client_id     = $config['client_id'];

            $client_secret = $config['client_secret'] ;

            $access_token  = $config['access_token'] ;

            $redirect_url  = $config['redirect_url'] ;

            $time                = time();

            $this->client_id     = $client_id;

            $this->client_secret = $client_secret;

            $this->access_token  = $access_token;

            $this->redirect_url        = $redirect_url;

            $this->login_url     = "https://www.linkedin.com/uas/oauth2/authorization?response_type=code&client_id=$client_id&redirect_uri=$this->redirect_url&state=$time&scope=w_share";



        }



        // function to redirect user to linkedin login page

        public function login(){
            header("location:$this->login_url");
            exit;
        }

          public function return_login_url(){

            return $this->login_url;
        }





        // function to get access token from linked

        public function getAccessToken( $code ) {



            if( $code ) {



                $method       = "POST";



                $url          = "https://www.linkedin.com/uas/oauth2/accessToken";



                $auth_header  = array(

                   'Host: www.linkedin.com',

                   'Content-Type: application/x-www-form-urlencoded',

                );



                $body         = array(

                    'grant_type'    => 'authorization_code',

                    'code'          => $code,

                    'redirect_uri'  => $this->redirect_url,

                    'client_id'     => $this->client_id,

                    'client_secret' => $this->client_secret

                );



                $body         = http_build_query($body);

                $response     = $this->httpRequest($url, $auth_header, $method, $body);

                $response_arr = json_decode($response);





                if( isset( $response_arr->access_token ) ){

                    return $this->access_token = $response_arr->access_token;

                }



                if( $this->debug && isset( $response_arr->error_description ) && isset( $response_arr->error_code ) ) {

                    echo "Error Code : ".$response_arr->error_code."<br>Error : ".$response_arr->error_description;

                }

            }



            return "";              

        }



        // Function to share post on linkedin 

        function sharePost( $comment = "", $title = "", $description = "", $submitted_url = "", $submitted_image_url = "" ) {

            // $this->access_token = "dsafsdfsdffffffffdfgsdfsdfsdfffffffffffdffsdfsdfsd";

            if(!isset( $this->access_token )){

               

                $this->login();



             } else {

               

                $method       = "POST";

                $url          = "https://api.linkedin.com/v1/people/~/shares?format=json";

                $body         = "{

                                  \"comment\": \"$title\",

                                  \"content\": {

                                    \"title\": \"$title\",

                                    \"description\": \"$description\",

                                    \"submitted-url\": \"$submitted_url\",  

                                    \"submitted-image-url\": \"$submitted_image_url\"

                                  },

                                  \"visibility\": {

                                    \"code\": \"anyone\"

                                  }  

                                }";



                $auth_header  = array(

                   'Host: api.linkedin.com',

                   'Connection: Keep-Alive',

                   "Authorization: Bearer $this->access_token",

                   "Content-Type: application/json",

                   "x-li-format: json"

                );



                $response     = $this->httpRequest($url, $auth_header, $method, $body);

                $response_arr = json_decode($response);

                if( isset( $response_arr->updateKey ) ) {



                    return true;



                } else {



                    if( isset( $response_arr->status )  && isset( $response_arr->message) )  {



                        $status            = $response_arr->status;

                        $message           = $response_arr->message;



                         if( $status == '401' && $message == "Invalid access token." ){

                           $this->login();

                         }

                    }

                }



             }



             return false;

        }        

    

        // Function to post http request through curl 

        private function httpRequest($url, $auth_header, $method, $body = NULL) {



            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $url);

            curl_setopt($curl, CURLOPT_POST, 1);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

            curl_setopt($curl, CURLOPT_HTTPHEADER, $auth_header );

            $data = curl_exec($curl);

            $header = curl_getinfo ( $curl );

            curl_close($curl);

            return $data;

        }

    } 



?>