<?php
    require_once 'MySQLDatabase.php';
    
    /*
     * test
     */
    class User extends DatabasObject {
        
        /*
        *  Defined protected because instantiate method in DatabaseObject to access them.
        */
        protected $id;
        protected $username;
        protected $password;
        protected $first_name;
        protected $last_name;
        protected $db_fields = array("username" => null, "password" => null, "first_name" => null, "last_name" => null);

        protected static $table_name = "users";

        /*
         * Properties accessors 
         */
        protected function get_id(){
            return $this->id;
        }
        protected function get_username(){
            return $this->username;
        }
        protected function get_first_name(){
            return $this->first_name;
        }
        protected function get_last_name(){
            return $this->last_name;
        }
        
        protected function set_username($value){
            $this->username = $value;
        }
        protected function set_password($value){
            $this->password = $value;
        }
        protected function set_first_name($value){
            $this->first_name = $value;
        }
        protected function set_last_name($value){
            $this->last_name = $value;
        }
        
        /*
         * Authenticating users
         */
        public static function authenticate(IDBAdapter $db, $username, $password){
            $username = $db->escape_query($username);
            $password = $db->escape_query($password);
            
            $query = "SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}' LIMIT 1";
            $result = self::find_by_sql($db, $query);
            return !empty($result) ? array_shift($result) : false;
        }
    }
?>
