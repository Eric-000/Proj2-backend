<?php

    class sqsModel {

        private $dbconn;

        public function __construct() {
            $dbURI = 'mysql:host='.'localhost'.';port=3306;dbname=' . 'proj2';
            $this->dbconn = new PDO($dbURI, 'root', '');
            $this->dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        function checkLogin($u, $p) {
            // Return uid if user/password tendered are correct otherwise 0
            $sql = "SELECT * FROM login WHERE username = :username";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->bindParam(':username', $u, PDO::PARAM_INT);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                $retVal = $stmt->fetch(PDO::FETCH_ASSOC);
                if(strlen($retVal['password']) > 0) {
                    if($retVal['password'] == $p) { // encrypt & decrypt
                        return Array('LoginID'=>$retVal['LoginID'],
                                   'name'=>$retVal['name'],
                                  'email'=>$retVal['email'],
                                   'username'=>$retVal['username'],
                                  'role'=>$retVal['role']);
                    } else {
                        return false;
                    }
                } else {
                    return Array('username'=>$retVal['username']);
                }
            } else {
                return false;
            }
        }
        function userExists($u) {
            $sql = "SELECT * FROM login WHERE username = :username";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->bindParam(':username', $u, PDO::PARAM_INT);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }
        function registerUser($logId,$name, $email, $username, $pass, $role) {
            // Retister user into system, assume validation has happened.
            // return UID created or false if fail
            $sql = "INSERT INTO login(name,email,username,password,role) VALUES (:name,:email,:username,:pass,:role);";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            $result = $stmt->execute();
            if($result === true) {
                return true;
            } else {
                return false;
            }
        }
        function addMovie( $Mname, $Mimage) {
            $sql = "INSERT INTO movie(movie_name,movie_image) VALUES (:movie_name,:movie_image);";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->bindParam(':movie_name', $Mname, PDO::PARAM_STR);
            $stmt->bindParam(':movie_image', $Mimage, PDO::PARAM_STR);
            $result = $stmt->execute();
            if($result === true) {
                return true;
            } else {
                return false;
            }
        }
        function getMID() {
            $sql = "SELECT * From movie";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            exit(json_encode($result));
        }
        
        function addShowing($Mid,$Amount,$Sfrom,$Sto) {
             $sql = "INSERT INTO showing(movie_id,amountofseats,showing_from_date,showing_to_date) VALUES(:moive_id,:amountofseats,showing_from_date,:showing_to_date);";
             $stmt = $this->dbconn->prepare("SELECT movie_id FROM movie WHERE movie_id = movie_id");
             $stmt->execute();
             $Mid = $row['movie_id'];
             $stmt->bindParam(':moive_id', $Mid, PDO::PARAM_STR);
             $stmt->bindParam(':amountofseats', $Amount, PDO::PARAM_STR);
             $stmt->bindParam(':showing_from_date', $Sfrom, PDO::PARAM_STR);
             $stmt->bindParam(':showing_to_date', $Sto, PDO::PARAM_STR);
             $result = $stmt->execute();
             if($result === true) {
                 return true;
            } else { 
                 return false;
            }
        }






        // function logEvent($uid, $url, $resp_code, $source_ip) {
        //     $sql = "INSERT INTO logtable (url, uid, response_code, ip_addr) 
        //         VALUES (:url, :uid, :resp_code, :ip);";
        //     $stmt = $this->dbconn->prepare($sql);
        //     $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
        //     $stmt->bindParam(':url', $url, PDO::PARAM_STR);
        //     $stmt->bindParam(':resp_code', $resp_code, PDO::PARAM_INT);
        //     $stmt->bindParam(':ip', $source_ip, PDO::PARAM_STR);
        //     $result = $stmt->execute();
        //     if($result === true) {
        //         return true;
        //     } else {
        //         return false;
        //     }
        // }
    }
?>
