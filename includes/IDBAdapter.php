<?php

/**
 *
 * @author rafael
 * NOTE : Every class implements this interface 
 * have to create connection and open it in its constructor
 */
interface IDBAdapter {        
  
        /*
         * Querying method
         */        
        public function query($sql);
        
        /*
         * Helper method : for fetching result
         */
        public function fetch_result($result);
        
        /*
         * Helper method : for getting number of rows for a query
         */
        public function num_rows($result);
        /*
         * Helper method : for getting number of affected rows
         */
        public function affected_rows();
        
        /*
         * Helper method : for getting id of the latest inserted row
         */
        public function inserted_id();
                
        /*
         * Method for closing the connection
         */
        public function close_connection();
}

?>
