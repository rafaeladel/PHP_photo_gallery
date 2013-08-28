<?php
    class Pagination {
        public $current_page;
        public $per_page;
        public $total_count;
        
        function __construct($current_page, $per_page=3, $total_count=0){
            $this->current_page = (int)$current_page;
            $this->per_page = (int)$per_page;
            $this->total_count = (int)$total_count;            
        }
        
        public function pages_count(){
            return ceil($this->total_count/$this->per_page);
        }
        
        public function get_offset(){
            return ($this->per_page * ($this->current_page - 1));
        }
        
        public function previous_page(){
            return $this->current_page - 1;
        }
        
        public function has_previous(){
            return $this->previous_page() >= 1;   
        }
    
        public function next_page(){
            return $this->current_page + 1;
        }
        
        public function has_next(){
            return $this->next_page() <= $this->pages_count();   
        }
        
    }
    

?>