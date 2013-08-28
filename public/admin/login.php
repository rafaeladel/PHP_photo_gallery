<?php
    require_once '../../includes/functions.php';
    $session = new Session();
    if($session->is_logged_in()){
        redirect_to("index.php");
    }
    if(isset($_POST["submit"])){
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        $db = new MySQLDatabase();        
        $found_user = User::authenticate($db, $_POST["username"], $_POST["password"]);
        if($found_user){
            $session->login($found_user);
            log_action("Login(Success)", $found_user->username);
            redirect_to("index.php");
        } else {
            log_action("Login(Failure)", $username);
            $message = "Invalid username or password.";
        }
    } else {
        
    }
?>

<?php get_template("admin_header"); ?>
            <h2>Staff Login</h2>
            <form action="login.php" method="post">
                <ul>
                    <li>
                        <label for="username" class="form_labels">Username: </label>
                        <input type="text" id="username" name="username"/>
                    </li>
                    <li>
                        <label for="password" class="form_labels">Password: </label>
                        <input type="text" id="password" name="password"/>
                    </li>
                </ul>    
                <input type="submit" name="submit" value="Submit"/>
            </form>
            
<?php get_template("footer"); ?>
