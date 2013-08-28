<?php
    require_once 'MySQLDatabase.php';
    
    class Photograph extends DatabasObject {
        
        /*
        *  Defined protected because instantiate method in DatabaseObject to access them.
        */
        protected $id;
        protected $filename;
        protected $type;
        protected $size;
        protected $caption;
        private $tmp_dir;
        protected $upload_dir = "photos";
        protected $db_fields = array("filename" => null, "type" => null, "size" => null, "caption" => null);
        public $errors = array();
        protected $upload_errors = array(
            UPLOAD_ERR_OK 		=> "No errors.",
            UPLOAD_ERR_INI_SIZE  	=> "Larger than upload_max_filesize.",
            UPLOAD_ERR_FORM_SIZE 	=> "Larger than form MAX_FILE_SIZE.",
            UPLOAD_ERR_PARTIAL 		=> "Partial upload.",
            UPLOAD_ERR_NO_FILE 		=> "No file.",
            UPLOAD_ERR_NO_TMP_DIR       => "No temporary directory.",
            UPLOAD_ERR_CANT_WRITE       => "Can't write to disk.",
            UPLOAD_ERR_EXTENSION 	=> "File upload stopped by extension."
	);

        protected static $table_name = "photographs";

        /*
         * Properties accessors 
         */
        protected function get_id(){
            return $this->id;
        }
        
        protected function get_filename(){
            return $this->filename;
        }
        protected function get_type(){
            return $this->type;
        }
        protected function get_size(){
            if($this->size < 1024){
              return $this->size . " B";  
            } elseif($this->size < 1048576){
                return round($this->size / 1024) . " KB";
            } elseif($this->size < 1073741824){
                return round($this->size / 1048576) . " MB";
            }
        }
        protected function get_caption(){
            return $this->caption;
        }
        protected function get_upload_dir(){
            return $this->upload_dir;
        }
        
        protected function set_filename($value){
            $this->filename = $value;
        }
        protected function set_type($value){
            $this->type = $value;
        }
        protected function set_size($value){
            $this->size = $value;
        }
        protected function set_caption($value){
            $this->caption = $value;
        }
        protected function set_upload_dir($value){
            $this->upload_dir = $value;
        }
        
        
        public function comments(IDBAdapter $db){
            return Comment::find_by_photograph($db, $this->id);
        }
        
        public function save(IDBAdapter $db){
            if(strlen($this->caption) > 255){
                array_push($this->errors, "Caption is too long.");
                return false;
            }
            
            if(empty($this->filename) || empty($this->tmp_dir)){
                array_push($this->errors, "Invalid file name or temp dir.");
                return false;
            }
                  
            if(!empty($this->errors)){
                return false;
            }
            
            $target_dir = SITE_ROOT.DS.$this->upload_dir;
            $target_path = $target_dir.DS.$this->filename;
            
            
            if(file_exists($target_path)){
                array_push($this->errors, "File already exists!");
                return false;
            } else {
                if(!file_exists($target_dir)){
                    mkdir($target_dir);
                }
            }
            if(move_uploaded_file($this->tmp_dir, $target_path)){
                if(parent::save($db)){
                    unset($this->tmp_dir);
                    return true;
                } else {
                    return false;
                }
            } else {
                array_push($this->errors, "Error while moving file to upload dir.");
                return false;
            }
        }
        
        public function attach_file($file){
            if(!$file || empty($file) || !is_array($file)){
                array_push($this->errors, "No file has been selected to upload.");
                return false;
            } elseif($file["error"] != 0){
                array_push($this->errors, $this->upload_errors[$file["error"]]);
                return false;
            } else {
                $this->filename = basename($file["name"]);
                $this->type = $file["type"];
                $this->size = $file["size"];
                $this->tmp_dir = $file["tmp_name"];
            }
        }
        
        public function destroy(IDBAdapter $db){
            $target_dir = SITE_ROOT.DS.$this->upload_dir;
            $target_path = $target_dir.DS.$this->filename;
            if(file_exists($target_path)){
                if(unlink($target_path)){
                    if($this->delete($db)){
                        $comments = $this->comments($db);
                        foreach($comments as $comment){
                            $comment->delete($db);
                        }
                        return true;  
                    } else {
                        return false;
                    }
                } else { return false; }
            } else { return false; }
        }
        
    }
?>
