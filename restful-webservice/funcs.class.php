<?php
require_once 'config.php';
class apiManagement{
    private $table = "users";
    public function __construct(){ //constructor
        
    }
    /**
     * getUsers retrieve all users info from DB
     * @global  $mysqli
     * @return array($row). Array containing user data
     */
    function getUsers(){
        global $mysqli;
        $row = array();
        $stmt = $mysqli->prepare("SELECT id,first_name,last_name,email FROM $this->table WHERE delete_flag=0");
        if($stmt && $stmt->execute()){
            $stmt->bind_result($id,$first_name,$last_name,$email);
            while($stmt->fetch()){
                $row[] = array('id'=>$id,'first_name'=>$first_name,'last_name'=>$last_name,'email'=>$email);
            }
            $stmt->close();
        }
        return $row;
    }
    /**
     * addUser insert a user record in DB
     * @global $mysqli $mysqli
     * @param type $userData array containing user info
     * @return int($retVal) 0 - Error,1 - Success
     */
    function addUser($userData){
        global $mysqli;
        $retVal = 0;
        if(empty($userData)){
            return $retVal;
        }
        $userData = json_decode($userData,true);
        $first_name = $userData['first_name'];
        $last_name = $userData['last_name'];
        $email = $userData['email'];
        $stmt = $mysqli->prepare("INSERT INTO ".$this->table." (first_name,last_name,email) VALUES (?,?,?)");
        $stmt->bind_param("sss", $first_name,$last_name,$email);
        $result = $stmt->execute();
        if($result){
           $retVal = 1; 
        }
        $stmt->close();
        return $retVal;
    }
    /**
     * updateUserData update a user's data in DB
     * @global $mysqli $mysqli
     * @param type $userData array containing user info to be updated
     * @return int($retVal) 0 - Error,1 - Success
     */
    function updateUserData($userData){
        global $mysqli;
        $retVal = 0;
        if(empty($userData)){
            return $retVal;
        }
        $userData = json_decode($userData,true);
        $first_name = $userData['first_name'];
        $last_name = $userData['last_name'];
        $email = trim($userData['email']);
        $stmt = $mysqli->prepare("UPDATE ".$this->table." SET first_name=?,last_name=? WHERE email = ?");
        $stmt->bind_param("sss", $first_name,$last_name,$email);
        $result = $stmt->execute();
        if($result){
            $retVal = 1;
        }
        $stmt->close();
        return $retVal;
    }
    /**
     * deleteUser delete a user from DB
     * @global $mysqli $mysqli
     * @param type string($email) delete a user on the basis of this param
     * @return type
     */
    function deleteUser($email){
        global $mysqli;
        $retVal = 0;
        $email = trim($email);
        $stmt = $mysqli->prepare("UPDATE ".$this->table." SET delete_flag=1 WHERE email = ?");
        $stmt->bind_param("s",$email);
        $result = $stmt->execute();
        if($result){
            $retVal = 1;
        }
        $stmt->close();
        return $retVal;
    }
}