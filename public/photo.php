<?php
    require_once '../includes/functions.php';
    $session = new Session();
    $db = new MySQLDatabase();
    if(isset($_GET["id"])){
        if(is_numeric($_GET["id"])){
            $photo = Photograph::find_by_id($db, $_GET["id"]);
            if(!$photo){
                redirect_to("index.php");
            }
            $img = DS."photo_gallery".DS.$photo->upload_dir.DS.$photo->filename;
        } else {
            redirect_to("index.php");
        }
    } else {
        redirect_to("index.php");
    }
    
    if(isset($_POST["submit"])){
        $author = trim($_POST["author"]);
        $body = trim($_POST["body"]);
        
        if(!empty($body)){
            $comment = Comment::make($photo->id, $author, $body);
            if($comment){
                $comment->save($db);
                redirect_to("photo.php?id=".$_GET["id"]);
            } else {
                $session->message = "Error while saving comment.";                
            }
        } else {
            $session->message = "Comment body cannot be empty.";
        }
    } else {
        $author = "";
        $body = "";
    }
    
    $all_comments = $photo->comments($db); 
?>

<?php get_template("public_header"); ?>
    <a id="back_btn" href="index.php">&lt;&lt;Back</a>
    <section id="single_image_wrapper">
        <div>
            <img src="<?php echo $img; ?>" alt="<?php echo $photo->caption; ?>" />
        </div>
        <p><?php echo $photo->caption; ?></p>
    </section>
    <br/>
    <hr/>
    <?php
        foreach($all_comments as $single_comment){
            echo "<h4>".htmlentities($single_comment->author)."</h4>";
            echo "<p>".datetime_to_text($single_comment->created)."</p>";
            echo "<p>".strip_tags($single_comment->body,'<strong><em><p>')."</p>";
        }
    ?>    
    <h3>Add a comment:</h3>
    <?php echo output_message($session->message); ?>
    <form action="<?php echo "photo.php?id=".$_GET["id"]; ?>" method="post">
        <label for="author" class="form_labels">Author:</label>
        <input type="text" name="author" id="author" value="<?php echo $author; ?>"/>
        
        <label for="body" class="form_labels">Comment:</label>
        <textarea name="body" id="comment_body" cols="40" rows="8"><?php echo $body; ?></textarea>
        
        <input type="submit" name="submit" value="Submit"/>
    </form>
<?php get_template("footer"); ?>