<?php
    require_once '../../includes/functions.php';
    $session = new Session();
    if(!$session->is_logged_in()){
        redirect_to("login.php");
    }
    $db = new MySQLDatabase();
    
    $current_page = !empty($_GET["page"]) && is_numeric($_GET["page"]) ? $_GET["page"] : 1;
    $per_page = 3;
    $total_photos = Photograph::count_all($db);
    
    $paginator = new Pagination($current_page, $per_page, $total_photos);
    
    $query = "SELECT * FROM photographs";
    $query .= " LIMIT " . $paginator->per_page;
    $query .= " OFFSET " . $paginator->get_offset();
    
    $photos = Photograph::find_by_sql($db, $query);

    
?>

<?php get_template("admin_header"); ?>
    <h1>Manage Photos:</h1>
    <?php echo output_message($session->message); ?>
    <a href="upload.php">Upload another photo</a>
    <table class="bordered">
        <tr>
            <th>Image</th>
            <th>Filename</th>
            <th>Size</th>
            <th>Caption</th>
            <th>Comments</th>
        </tr>
    <?php foreach($photos as $photo) { $img = DS."photo_gallery".DS.$photo->upload_dir.DS.$photo->filename; ?>
        <tr>
            <td>
                <a href="<?php echo $img; ?>">
                    <img src="<?php echo $img; ?>" alt="<?php echo $photo->caption; ?>" />
                </a>
            </td>
            <td>
                <?php echo $photo->filename; ?>
            </td>
            <td>
                <?php echo $photo->size; ?>
            </td>
            <td>
                <?php echo $photo->caption; ?>
            </td>            
            <td>
                <a href="manage_comments.php?id=<?php echo $photo->id; ?>"><?php echo count($photo->comments($db)); ?></a>
            </td>
            <td>
                <a href="delete_photo.php?id=<?php echo $photo->id; ?>">Delete</a>
            </td>
        </tr>   
    <?php } ?>
    </table>
    <br/>
    <section style="clear:both;">
    <?php
        if($paginator->has_previous()){
            echo " <a href=manage_photos.php?page=".$paginator->previous_page().">Previous</a> ";
        }
        
        for($i = 1; $i <= $paginator->pages_count(); $i++){
            if($i == $current_page){
                echo "<span>{$i}</span>";
            } else {
                echo " <a href=manage_photos.php?page={$i}>{$i}</a> ";
            }
        }
        
        if($paginator->has_next()){
            echo " <a href=manage_photos.php?page=".$paginator->next_page().">Next</a> ";
        }
    ?>
    </section>
<?php get_template("footer"); ?>