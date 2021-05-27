<?php

    class sqsSession {
        // attributes will be stored in session, but always test incognito
        private $last_visit = 0;
        private $last_visits = Array();

        private $logId = 0;
        private $name;
        private $email;
        private $username;
        private $role;
        private $user_token;

        private $origin;

        public function __construct() {
            $this->origin = 'http://localhost/';
        }
        public function is_rate_limited() {
            if($this->last_visit == 0) {
                $this->last_visit = time();
                return false;
            }
            if($this->last_visit == time()) {
                return true;
            }
            return false;
        }
        public function login($username, $password) {
            global $sqsdb;

            $res = $sqsdb->checkLogin($username, $password);
            if($res === false) {
                return false;
            } elseif(count($res) > 1) {
                $this->logId = $res['LoginID'];
                $this->user_token = md5(json_encode($res));
                return Array('username'=>$res['username'],
                'name'=>$res['name'],
                'email'=>$res['email'],
                'role'=>$res['role'],
                'Hash'=>$this->user_token);
            } elseif(count($res) == 1) {
                $this->logId = $res['LoginID'];
                $this->user_token = md5(json_encode($res));
                return Array('Hash'=>$this->user_token);
            }
        }
        public function register($name, $email, $username, $pass, $role) {
            global $sqsdb;

                if($sqsdb->registerUser($this->$logId, $name, $email, $username, $pass, $role)) {
                    return true;
                } else {
                    return 0;
                }

            // call the dbobject for SQL
        }


        public function addmovie1($Mname, $Mimage) {
            global $sqsdb;

            if($sqsdb->addMovie($Mname, $Mimage)) {
                return true;
            } else {
                return 0;
            }
        }

        public function selectMID() {
            global $sqsdb;
            $sqsdb->getMID();
            return $sqsdb;
        }


        public function addShowing1($Mid,$Amount,$Sfrom,$Sto) {
            global $sqsdb;

            if($sqsdb->addShowing($Mid,$Amount,$Sfrom,$Sto)) {
                return true;
            } else {
                return 0;
            }
        }

        
        public function isLoggedIn() {
            if($this->user_id === 0) {
                return false;
            } else {
                return Array('Hash'=>$this->user_token);
            }
        }
        public function logout() {
            $this->user_id = 0;
        }
        public function validate($type, $dirty_string) {
        }
        public function logEvent() {
        }
    }
?>
