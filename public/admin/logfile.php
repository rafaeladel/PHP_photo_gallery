<?php
    require_once '../../includes/functions.php';
    $session = new Session();
    if(!$session->is_logged_in()){
        redirect_to("login.php");
    }
    
    if(isset($_GET["clear"])){
        if($_GET["clear"] == "true"){
            $db = new MySQLDatabase();
            $usr = User::find_by_id($db, $session->user_id);
            clear_log($usr->username);
        }
        redirect_to("logfile.php");
    }
    
?>

<?php get_template("admin_header"); ?>
<a id="back_btn" href="index.php">&lt;&lt;Back</a>
<h1>Log:</h1>
<a href="logfile.php?clear=true">Clear</a>
<br>
<section id="log_container">
    <?php 
        get_log();
    ?>
</section>
<?php get_template("footer"); ?>

