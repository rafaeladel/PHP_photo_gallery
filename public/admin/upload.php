<?php
    require_once '../../includes/functions.php';
    $session = new Session();
    if(!$session->is_logged_in()){
        redirect_to("login.php");
    }
    $db = new MySQLDatabase();
    if(isset($_POST["submit"])){
        $photo = new Photograph();
        $photo->attach_file($_FILES["file_upload"]);
        $photo->caption = trim($db->escape_query($_POST["caption"]));
        if($photo->save($db)){
            $session->message = "File uploaded successfuly";
            redirect_to("manage_photos.php");
        } else {
            $session->message = join("<br>", $photo->errors);
        }
    }
?>

<?php get_template("admin_header"); ?>
    <h1>Upload Photo:</h1>
    <?php echo output_message($session->message); ?>
    <form action="upload.php" enctype="multipart/form-data" method="post">
        <input type="hidden" name="MAX_FILE_SIZE" value="2000000"/>
        <input type="file" name="file_upload"/>
        <label for="caption">Caption:</label>
        <input type="text" id="caption" class="form_labels" name="caption" value=""/>
        <input type="submit" name="submit" value="Upload"/>
    </form>
<?php get_template("footer"); ?>