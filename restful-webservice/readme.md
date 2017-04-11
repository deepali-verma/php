## RetsAPI 
    RetsAPI - Simple authentcation based retsful api, using Slim framework as middleware.

### Authentication
    \Slim\Slim::registerAutoloader();
    $app = new \Slim\Slim(); //Slim Object
    /**
     * authSiteUser this is the middleware function that gets to validate the api request
     * @param \Slim\Route $route Slim route variable
     */
    function authSiteUser(\Slim\Route $route){//Slim middleware
        $app = \Slim\Slim::getInstance();
        if(validateUser() === false) 
        {
            $app->halt(401);//Unauthorized access
        }
    }

    /**
     * validateUser check if the request is authentic
     * @return boolean
     */
    function validateUser(){
        $app = \Slim\Slim::getInstance();
        $headers = apache_request_headers();
        $auth_token = "";
        if(isset($headers['Authorization'])){
            $auth_token = $headers['Authorization'];
        }
        if($auth_token == ""){
            return false;
        }
        $algorithm = 'HS256';
        $secret = SECRET_KEY;
        $time = time();
        $leeway = 5; // seconds
        $ttl = 30; // seconds
        $claims = getVerifiedClaims($auth_token,$time,$leeway,$ttl,$algorithm,$secret);
        if($claims['sub'] == '1234567890' && $claims['name'] == 'test_name'){
            return true;
        }
        return false;
    }

    /**
     * getVerifiedClaims verifies the token received from client "Authorization" header
     * @param type $token Token received from client
     * @param type $time start time
     * @param type $leeway value used to check if token expired
     * @param type $ttl expire time
     * @param type $algorithm algorithm used to decrypt.HS256
     * @param type $secret Predefined client secet key
     * @return $claims claims array
     */
    function getVerifiedClaims($token,$time,$leeway,$ttl,$algorithm,$secret) {
        $algorithms = array('HS256'=>'sha256','HS384'=>'sha384','HS512'=>'sha512');
        if (!isset($algorithms[$algorithm])) return false;
        $hmac = $algorithms[$algorithm];
        $token = explode('.',$token);
        if (count($token)<3) return false;
        $header = json_decode(base64_decode(strtr($token[0],'-_','+/')),true);
        if (!$secret) return false;
        if ($header['typ']!='JWT') return false;
        if ($header['alg']!=$algorithm) return false;
        $signature = bin2hex(base64_decode(strtr($token[2],'-_','+/')));
        if ($signature!=hash_hmac($hmac,"$token[0].$token[1]",$secret)) return false;
        $claims = json_decode(base64_decode(strtr($token[1],'-_','+/')),true);
        if (!$claims) return false;
        if (isset($claims['nbf']) && $time+$leeway<$claims['nbf']) return false;
        if (isset($claims['iat']) && $time+$leeway<$claims['iat']) return false;
        if (isset($claims['exp']) && $time-$leeway>$claims['exp']) return false;
        if (isset($claims['iat']) && !isset($claims['exp'])) {
            if ($time-$leeway>$claims['iat']+$ttl) return false;
        }
        return $claims;
    }

### Serving User Request
    /**
     * GET request.List all users data
     */
    $app->get('/getUser','authSiteUser',function(){
        require_once 'funcs.class.php';
        $api = new apiManagement();
        $retVal = $api->getUsers();
        echo json_encode($retVal);
    });

    /**
     * POST request. Insert a new record
     */
    $app->post('/addUser/:userData','authSiteUser',function($userData){
        require_once 'funcs.class.php';
        $retVal = 0;
        $api = new apiManagement();
        if($api->addUser($userData)){
            $retVal = 1;
        }
        echo json_encode($retVal);
    });

    /**
     * PUT request.Update a user record.
     */
    $app->put('/updateUser/:userData','authSiteUser',function($userData){
        require_once 'funcs.class.php';
        $retVal = 0;
        $api = new apiManagement();
        if($api->updateUserData($userData)){
            $retVal = 1;
        }
        echo json_encode($retVal);
    });

    /**
     * DELETE request. Delete a record from DB.
     */
    $app->delete('/deleteUser/:email','authSiteUser',function($email){
        require_once 'funcs.class.php';
        $retVal = 0;
        $api = new apiManagement();
        if($api->deleteUser($email)){
            $retVal = 1;
        }
        echo json_encode($retVal);
    });

### Dependecies
        PHP 5.5
        MySql 5.6.17

### Database
    Need to create a database named rets_api and improt rets_api.sql file to that db.

### Database configurations - db_config.php
    Update database credentials
    define ('DB_USER', "");
    define ('DB_PASSWORD', "");
    define ('DB_DATABASE', "rest_api");
    define ('DB_HOST', "");

## API Reference
    1. Slim Framework.
