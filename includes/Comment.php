<?php
    require_once 'MySQLDatabase.php';
    
    class Comment extends DatabasObject {
        
        /*
        *  Defined protected because instantiate method in DatabaseObject to access them.
        */
        protected $id;
        protected $photograph_id;
        protected $created;
        protected $author;
        protected $body;
        protected $db_fields = array("photograph_id" => null, "created" => null, "author" => null, "body" => null);

        protected static $table_name = "comments";

        /*
         * Properties accessors 
         */
        protected function get_id(){
            return $this->id;
        }        
        protected function get_photograph_id(){
            return $this->photograph_id;
        }        
        protected function get_created(){
            return $this->created;
        }
        protected function get_author(){
            return $this->author;
        }
        protected function get_body(){
            return $this->body;
        }
       
        
        protected function set_id($value){
            $this->id = $value;
        }
        protected function set_photograph_id($value){
            $this->photograph_id = $value;
        }
        protected function set_created($value){
            $this->created = $value;
        }
        protected function set_author($value){
            $this->author = $value;
        }
        protected function set_body($value){
            $this->body = $value;
        }
        
        public static function find_by_photograph(IDBAdapter $db, $photo_id){
            $query = "SELECT * FROM comments WHERE photograph_id =" . $db->escape_query($photo_id) . " ORDER BY created DESC";
            $comments = Comment::find_by_sql($db, $query);
            return $comments;
        }
       
        public static function make($photo_id, $author = "Anonymous", $body=""){
            if(!empty($photo_id) && !empty($body)){
                $comment = new Comment();
                $comment->photograph_id = $photo_id;
                $comment->author = empty($author) ? "Anonymous" : $author;
                $comment->created = date("Y-n-d H:i:s", time());
                $comment->body = $body;
                return $comment;
            } else {
                return false;
            }
        }
    }
?>
