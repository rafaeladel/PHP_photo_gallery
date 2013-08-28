<?php
    require_once '../includes/functions.php';
    $session = new Session();
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

<?php get_template("public_header"); ?>
    <section class="gallery_wrapper">
        <?php foreach($photos as $photo) {
            $img = DS."photo_gallery".DS.$photo->upload_dir.DS.$photo->filename;
            $link_to_photo = "photo.php?id=". $photo->id;
        ?>
        <div>
            <a href="<?php echo $link_to_photo; ?>" alt="<?php echo $photo->caption; ?>">
                    <img src="<?php echo $img; ?>" alt="<?php echo $photo->caption; ?>" />
            </a>
            <p>
                <?php echo $photo->caption; ?>
            </p>
        </div>
        <?php } ?>
    </section>
    <section style="clear:both;">
    <?php
        if($paginator->has_previous()){
            echo " <a href=index.php?page=".$paginator->previous_page().">Previous</a> ";
        }
        
        for($i = 1; $i <= $paginator->pages_count(); $i++){
            if($i == $current_page){
                echo "<span>{$i}</span>";
            } else {
                echo " <a href=index.php?page={$i}>{$i}</a> ";
            }
        }
        
        if($paginator->has_next()){
            echo " <a href=index.php?page=".$paginator->next_page().">Next</a> ";
        }
    ?>
    </section>
    <br/>
<?php get_template("footer"); ?>