<?php

/*
 * This class contains the most common database related methods
 * to be used by User, Comment and Photograph classes.
 */
    
    class DatabasObject {

        /*
         * This magic method is to call properties with
         * $user->id for example, with respect to the private scoped
         * properties.
         * And so the __set() method too. But for setting fields.
         */
        public function __get($property){
            $method = "get_" . $property;
            //we call method exists to rescpect private scoped properties
            if(method_exists($this, $method)){
                //eg. get_id(), get_username(), etc..
                return $this->{$method}();
            } else {                
                throw new Exception("Cannot get property : {$property}");
                
            }
        }

        public function __set($property, $value){
            $method = "set_" . $property;
            if(method_exists($this, $method)){
                $this->{$method}($value);
            } else {
                throw new Exception("Cannot set property : {$property}");
            }
        }
        
        public static function find_all(IDBAdapter $db){
            $query = "SELECT * FROM " . static::$table_name ;
            $result = static::find_by_sql($db, $query);
            return $result;
        }
        
        public static function find_by_id(IDBAdapter $db, $id){
            $query ="SELECT * FROM ".static::$table_name." WHERE id = " . $id. " LIMIT 1";
            $result_array = static::find_by_sql($db, $query);
            //returning only the first user of the array. Or false if not found
            return !empty($result_array) ? array_shift($result_array) : false;
        }
        
        /*
         * Find user by sql if the above is not enough
         */
        public static function find_by_sql(IDBAdapter $db, $sql){
            $result = $db->query($sql);
            //array in case of querying for more than one user
            //thus, more than one user object instantiation.
            $result_array = array();
            $result_set = $db->fetch_result($result);
            foreach($result_set as $record){
                array_push($result_array, static::instantiate($record));
            }
            return $result_array;
        }
        
        /*
         * For producing a User object and populating its properties.
         */
        private static function instantiate($record){
            $object = new static;
            foreach($record as $name => $value){
                //in property_exists i used __CLASS__ not self. 
                //since __CLASS__ returns class name as string
                //self returns class it self. property_exists wants the string
                if(isset($object->$name) || property_exists(get_called_class(), $name)){
                    $object->$name = $value;
                }
            }
            return $object;
        }

        private function populate_db_fields(IDBAdapter $db){
            $all_att = get_object_vars($this);
            foreach ($all_att as $name => $value) {
                if(array_key_exists($name, $this->db_fields)){
                    $this->db_fields[$name] = $value;
                }
            }
        }
        
        public function create(IDBAdapter $db){
            $this->populate_db_fields($db);
            $query = "INSERT INTO ". static::$table_name ." (";
            $query .= join(", ",array_keys($this->db_fields));
            $query .= ") VALUES (";
            $query .= join(",",str_split(str_repeat("?",count($this->db_fields))));
            $query .= ")";
            $db->query($query, array_values($this->db_fields));
            $this->id = $db->inserted_id();
            if(isset($this->id)){
                return true;
            } else {
                return false;
            }
        }

        public function update(IDBAdapter $db){
            $this->populate_db_fields($db);
            $conditions = array();
            foreach ($this->db_fields as $name => $value) {
                $line = "{$name}= ? ";
                array_push($conditions, $line);
            }
            $query = "UPDATE ". static::$table_name ." SET ";
            $query .= join(", ",$conditions);
            $query .= " WHERE id=" . $this->id;
            $db->query($query, array_values($this->db_fields));
            if($db->affected_rows() == 1){
                return true;
            } else {
                return false;
            }
        } 

        public function delete(IDBAdapter $db){
            $query = "DELETE FROM ". static::$table_name;
            $query .= " WHERE id=". $this->id;
            $query .= " LIMIT 1";
            $db->query($query);
            if($db->affected_rows() == 1){
                return true;
            } else {
                return false;
            }
        }

        public function save(IDBAdapter $db){
           return !isset($this->id) ? $this->create($db) : $this->update($db);
        }
        
        public static function count_all(IDBAdapter $db){
            $query = "SELECT COUNT(*) FROM ". static::$table_name;
            $result = $db->query($query);
            $row = $db->fetch_result($result);
            return array_shift($row);
        }
    }

?>
