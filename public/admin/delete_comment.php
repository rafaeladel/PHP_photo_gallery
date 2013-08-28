<?php
    require_once '../../includes/functions.php';
    $session = new Session();
    if(!$session->is_logged_in()){
        redirect_to("login.php");
    }
    $db = new MySQLDatabase;
    if(!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
        redirect_to("manage_comments.php");
    }       
            
    $comment = Comment::find_by_id($db , $_GET["id"]);
    if(!$comment){
       redirect_to("manage_comments.php");            
    } else {
        if($comment->delete($db)){
            $session->message = "Comment deleted.";
        } else {
            $session->message = "Failed to delete comment.";
        }
    }
    redirect_to("manage_comments.php?id=" .$comment->photograph_id);
?>