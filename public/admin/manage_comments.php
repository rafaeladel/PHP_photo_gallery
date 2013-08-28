<?php
    require_once '../../includes/functions.php';
    $session = new Session();
    if(!$session->is_logged_in()){
        redirect_to("login.php");
    }
    $db = new MySQLDatabase;
    if(!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
        redirect_to("manage_photos.php");
    }       
            
    $photo = Photograph::find_by_id($db, $_GET["id"]);
    if(!$photo){
       redirect_to("manage_photos.php");            
    }
    
    $comments = $photo->comments($db);
    
?>

<?php get_template("admin_header"); ?>
    <h1>Manage Comments:</h1>
    <?php echo output_message($session->message); ?>
    <?php foreach($comments as $comment): ?>
        <div>
            <h3><?php echo htmlentities($comment->author); ?></h3>
            <p><?php echo datetime_to_text($comment->created); ?></p>
            <p><?php echo strip_tags($comment->body, '<strong><p><pre>'); ?></p>
            <a href="delete_comment.php?id=<?php echo $comment->id; ?>">Delete comment</a>
        </div>
    <?php endforeach; ?>
    <br/>
<?php get_template("footer"); ?>