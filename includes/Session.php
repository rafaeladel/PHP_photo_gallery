<?php

    class Session {
        private $logged_in = false;
        public $user_id;
        private $message;   
        
        function __construct(){
            session_start();
            $this->check_login();
        }
        
        private function get_message(){
            if(isset($_SESSION["message"])){
                $this->message = $_SESSION["message"];
                unset($_SESSION["message"]);
                return $this->message;
            }else{
                unset($this->message);
                return "";
            }
        }
        
        private function set_message($value){
            $this->message = $_SESSION["message"] = $value;
        }
        
        public function __get($property){
            if($property == "user_id"){
                return $this->user_id;
            }
           $method = "get_" . $property;
           if(method_exists($this, $method)){
               return $this->{$method}();
           } else {                
               throw new Exception("Cannot get property : {$property}");
           }
       }

       public function __set($property, $value){
            if($property == "user_id"){
                $this->user_id = $value;
                return;
            }
           $method = "set_" . $property;
           if(method_exists($this, $method)){
               $this->{$method}($value);
           } else {
               throw new Exception("Cannot set property : {$property}");
           }
       } 
        
        public function is_logged_in(){
            return $this->logged_in;
        }
        
        private function check_login(){
            if(isset($_SESSION["user_id"])){
                $this->user_id = $_SESSION["user_id"];
                $this->logged_in = true;
            } else {
                unset($this->user_id);
                $this->logged_in = false;
            }
        }
        
        public function login(User $user){
            $this->user_id = $_SESSION["user_id"] = $user->id;
            $this->logged_in = true;
        }
        
        public function logout(){
            unset($_SESSION["user_id"]);
            unset($this->user_id);
            $this->logged_in = false;
        }
    }
?>
