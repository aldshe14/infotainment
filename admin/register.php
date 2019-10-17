<?php
    // Initialize the session

    require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    $username = $password = $confirm_password = "";
    $username_err = $password_err = $confirm_password_err = "";

//If the POST var "register" exists (our submit button), then we can
//assume that the user has submitted the registration form.
if(isset($_POST['register'])){

    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        $sql = "SELECT u_id FROM tb_pres_users WHERE u_email = :username";
        if($stmt = $con->prepare($sql)){
            $param_username = trim($_POST["username"]);
            $stmt->bindParam(':username', $param_username);
            if($stmt->execute()){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if($row){
                    echo "<div id='hide' class=\"alert alert-danger \">";
                    echo "<p>This username is already taken.</p>";
                    echo "</div>";
                    $username_err = "This username is already taken.";
                }
                else   
                    $username = trim($_POST["username"]);
            }else{
                echo "<div id='hide' class=\"alert alert-danger \">";
                echo "<p>Oops! Something went wrong. Please try again later.</p>";
                echo "</div>";
            }
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        echo "<div id='hide' class=\"alert alert-danger \">";
        echo "<p>Password must have atleast 6 characters.</p>";
        echo "</div>";
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        echo "<div id='hide' class=\"alert alert-danger \">";
        echo "<p>Please confirm password.</p>";
        echo "</div>";
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            echo "<div id='hide' class=\"alert alert-danger \">";
            echo "<p>Password did not match.</p>";
            echo "</div>";
            $confirm_password_err = "Password did not match.";
        }
    }

    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        $sql = "INSERT INTO tb_pres_users (u_email, u_pswd,u_role) VALUES (:username, :password,:p)";
        if($stmt = $con->prepare($sql)){
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $stmt->bindValue(':username', $param_username);
            $stmt->bindValue(':password', $param_password);
            $stmt->bindValue(':p', "555");
            if($stmt->execute()){
                session_destroy();
                echo "<div id='hide' class=\"alert alert-success \">";
                echo "<p>User wurde erfolgreich angelegt! Redirecting ...</p>";
                echo "</div>";
                
                header('Refresh: 2; URL=signin.php');
                //header("location: login.php");
            } else{
                echo "<div id='hide' class=\"alert alert-danger \">";
                echo "<p>Something went wrong. Please try again later.</p>";
                echo "</div>";
            }
        }
        
    }
}
    

?>

        
        <div class="container">
        <h1 class="mt-4">Sign Up</h1>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <?php
                if(isset($_SESSION["role"]) && $_SESSION["role"] == 1){
                    echo '<label>Role</label><br>
                    <select name="role">
                        <option value="0">User</option>
                        <option value="1">Admin</option>
                    </select><br><br>';
                }
            ?>
            <div class="form-group">
                <input type="submit" class="btn btn btn-dark"  name="register" value="Register">
                <input type="reset" class="btn btn-outline-dark " value="Reset">
            </div>
        </form>
    </div>    


    <?php
    require_once "footer.php";
?>