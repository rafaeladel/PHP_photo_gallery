<?php
    require_once '../../includes/functions.php';
    $session = new Session();
    if(!$session->is_logged_in()){
        redirect_to("login.php");
    }
    $db = new MySQLDatabase();
    if(isset($_GET["id"])){
        if(is_numeric($_GET["id"])){
            $photo = Photograph::find_by_id($db, $_GET["id"]);
            if($photo->destroy($db)){
                $session->message = "Photo : {$photo->filename} has been deleted.";                
            } else {
                $session->message = "Photo deletion failed!";
            }            
        }
    }
    $db->close_connection();
    redirect_to("manage_photos.php");
?>  