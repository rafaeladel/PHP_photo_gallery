<?php
    
    /*
     * Definning important functions for locating files
     */
    defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);
    defined("SITE_ROOT") ? null : define("SITE_ROOT", DS."var".DS."www".DS."photo_gallery");
    defined("LIB_PATH") ? null : define("LIB_PATH", SITE_ROOT.DS."includes");
    defined("LOG_PATH") ? null : define("LOG_PATH", DS."var".DS."www".DS."photo_gallery".DS."logs");

    function redirect_to($destination = NULL){
        if($destination != NULL){
            header("Location: {$destination}");
            exit;
        }
    }
    
    function output_message($message = ""){
        return !empty($message) ? "<p class=\"message\">{$message}</p>" : "" ;
    }

    function __autoload($classname){       
        $file = LIB_PATH.DS."{$classname}.php";
        if(file_exists($file)){
            require_once "{$file}";
        } else {            
            die("Cannot find file : {$file}");
        }
    }
    
    function get_template($template = ""){
        $file = LIB_PATH.DS."layout".DS.$template.".php";
        include "{$file}";
    }
    
    function log_action($action, $msg){
        $dir_path = LOG_PATH;
        $file_path = LOG_PATH.DS."log.txt";
        if(!file_exists($dir_path)){
            mkdir($dir_path, 0777);
        }
        if(is_readable($file_path) && is_writable($file_path)){
            $handle = fopen($file_path, "ab");
            fwrite($handle , date("Y-n-d H:i:s", time()). " | {$action}: {$msg}"."\r\n");
            fclose($handle);
        } else {
            echo "Log file is not readable or writable";
        }        
    }
    
    function get_log(){
        $dir_path = LOG_PATH;
        $file_path = LOG_PATH.DS."log.txt";
        if(file_exists($dir_path)){
            if(is_readable($file_path)){
                $handle = fopen($file_path, "r");
                $content = fread($handle, filesize($file_path));
                echo nl2br($content);
            } else {
                echo "log file is not readable";
            }
        } else {
            echo "Log file does not exist." ;
        }
    }

    function clear_log($usr){
        $dir_path = LOG_PATH;
        $file_path = LOG_PATH.DS."log.txt";
        if(!file_exists($dir_path)){
            mkdir($dir_path, 0777);
        }
        if(is_readable($file_path) && is_writable($file_path)){
            file_put_contents($file_path, '');
            log_action("LOG CLEARED BY", $usr);
        } else {
            echo "Log file is not readable/writable";
        }        
    }
    
    function datetime_to_text($datetime=""){
        $unixdatetime = strtotime($datetime);
        return strftime("%B %d %Y at %I:%M %p", $unixdatetime);
    }
    
?>
