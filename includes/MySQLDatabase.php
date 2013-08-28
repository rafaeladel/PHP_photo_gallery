<?php

/*
 * Class for the database 
 */

    //For getting database constants
    include 'db_config.php';
    
    class MySQLDatabase implements IDBAdapter {
        
        public $last_query = null;
        private $connection = null;
        
        function __construct(){
            $this->open_connection();
        }
        
        /*
         * $connection getter 
         */
        public function get_connection(){
            return isset($this->connection) ? $this->connection : null;
        }
        
        
        /*
         * Method for openning the connection 
         */
        private function open_connection(){
            $this->connection = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
            if(!$this->connection){
                die("Error while openning the connection : " . mysqli_error());
            }
        }
                
        /*
         * Querying method
         */        
        public function query($sql){
            $this->last_query = $sql;
            $result = mysqli_query($this->connection, $sql);
            $this->confirm_query($result);
            return $result;
        }
        
        /*
         * Helper method : for confirming query
         */
        private function confirm_query($result){
            if(!$result){
                $output = "<hr>";
                $output .= mysqli_error($this->connection);
                $output .= "<hr>";
                $output .= "Last query : " . $this->last_query;
                $output .= "<hr>";
                die($output);                
            }
        }
        
        /*
         * Helper method : for fetching result
         */
        public function fetch_result($result){
            return mysqli_fetch_assoc($result);
        }
        
        /*
         * Helper method : for getting number of rows for a query
         */
        public function num_rows($result){
            return mysqli_num_rows($result);
        }
        
        /*
         * Helper method : for getting number of affected rows
         */
        public function affected_rows(){
            return mysqli_affected_rows($this->connection);
        }
        
        /*
         * Helper method : for getting id of the latest inserted row
         */
        public function inserted_id(){
            return mysqli_insert_id($this->connection);
        }
        
        /*
         * Helper method : for escaping sql queries 
         */
        public function escape_query($str){
            return mysqli_real_escape_string($this->connection,$str);
        }
        
        /*
         * Method for closing the connection
         */
        public function close_connection(){
            if(isset($this->connection)){
                mysqli_close($this->connection);
            }
        }
    }

?>
