<?php
    require_once '../../includes/functions.php';
    
    $session = new Session();
    if(!$session->is_logged_in()){
        redirect_to("login.php");
    }
?>

<?php get_template("admin_header"); ?>

<ul>
    <li>
        <a href="logfile.php">Log file</a>
    </li>
    <li>
        <a href="logout.php">Logout</a>
    </li>
</ul>
            
<?php get_template("footer"); ?>
