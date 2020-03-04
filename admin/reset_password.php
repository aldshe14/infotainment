<?php
	require_once "connection.php";
    require_once "header.php";
    //require_once "navigation.php";
    
    if(isset($_GET['id'])){
        $id = explode("-",$_GET['id']);
        $sql = "Select p_id FROM tb_infotainment_password_reset where id=:id and u_id=:uid and timestampdiff(hour,expire,now())<0;";
        $stmt = $con->prepare($sql);
        if(isset($id[1])){
            $stmt->bindValue(':id', $id[1]);
            $stmt->bindValue(':uid', $id[0]);
            if($stmt->execute()){
                if($stmt->rowCount()==0){
                    echo "";
                    echo "<div class=\"alert alert-danger \">";
                        echo "<p>This link is not valid anymore. Please request a <a href='reset.php'>new link</a> or go to <a href='signin.php'>signin</a> page.</p>";
                        echo "</div>";
                    die();
                }
            }
        }else if(!isset($id[0])){
            echo "";
            echo "<div class=\"alert alert-danger \">";
                echo "<p>This link is not valid anymore. Please request a <a href='reset.php'>new link</a> or go to <a href='signin.php'>signin</a> page.</p>";
                echo "</div>";
            die();
        }
    }else if(isset($_POST['login']) && $_SERVER["REQUEST_METHOD"] == "POST"){
        echo "in";
        $passwordConfirm_error = false;
        $password_error = false;

        // Provo nese passwordi eshte bosh
        if(empty(trim($_POST["password"]))){
            $password_text = "Ju lutem shkruani passwordin tuaj.";
            $password_error = true;
        } else{
            $password = trim($_POST["password"]);
        }
        
        // Provo nese passwordConfirmi eshte bosh
        if(empty(trim($_POST["passwordConfirm"]))){
            $passwordConfirm_text = "Ju lutem konfirmoni passwordin tuaj.";
            $passwordConfirm_error = true;
        } else{
            $passwordConfirm = trim($_POST["passwordConfirm"]);
        }

        if($password == $passwordConfirm){
            if(!$passwordConfirm_error && !$password_error){
                if($password!='12345678' && (strlen($password)>7 && strlen($password)<21)){
                    $sql = "UPDATE tb_infotainment_users SET u_pswd = :pswd WHERE u_id = :id";
                    $stmt = $con->prepare($sql);
                    //Bind value.
                    $stmt->bindValue(':pswd', password_hash($password,PASSWORD_DEFAULT));
                    $stmt->bindValue(':id', $_GET['uid']);

                    
                    try {
                        $stmt->execute();
                        $sql = "UPDATE tb_infotainment_password_reset set expire=DATE_SUB(now(),INTERVAL 5 DAY) where u_id=:id;";
                        $stmt = $con->prepare($sql);
                        $stmt->bindValue(':id', $_GET['uid']);
                        $stmt->execute();
                        
                        $query = "Select * from tb_infotainment_users where u_id = :id;";
                        $pdo = $con->prepare($query);
                        $pdo->bindValue(':id', $_GET['uid']);
                        $pdo->execute();
                        $user = $pdo->fetch(PDO::FETCH_ASSOC);
                        $_SESSION['user_id'] = $user['u_id'];
                        $_SESSION['loggedin'] = true;
                        $_SESSION['email'] = $user['u_email'];
                        $_SESSION['role'] = $user['u_role'];
                        header('Location: index.php');
                        $login = true;
                    }catch (PDOException $e) {
                        $passwordConfirm_text = "E-Mail konnte nicht gesendet werden.<br>";
                        $passwordConfirm_error = true;
                    }
                        exit();    
                }else if(strlen($password)<8 || strlen($password)>20){
                    $password_text = "Das Passwort muss aus (8-20) Zeichen bestehen.<br>";
                    $password_error = true;
                    $email_error = false;
                }   
                else{
                    $passwordConfirm_text = "Bitte wählen Sie ein anderes Passwort als 12345678.<br>";
                    $passwordConfirm_error = true;
                }
            }
        }else{
                $passwordConfirm_text = "Passwörter stimmen nicht überein.<br>";
                $passwordConfirm_error = true;
                $password_error = false;
        }
    }else{
        header('location: signin.php');
    }

?>

<link rel="stylesheet" href="css/signin.css">
<form class="form-signin" action="?uid=<?php echo $id[0].'-'.$id[1]; ?>" method="POST">
    <img class="mb-4" src="img/logo_b.png" alt="" width="100%" height="auto">

    <h1 class="h3 mb-3 font-weight-normal text-center">Passwort ändern</h1>
    
    <label for="inputpassword" class="sr-only">Passwort</label>
    <input type="password" id="inputpassword" class="form-control" placeholder="Passwort" name="password" 
    <?php 
        if(isset($password))
            echo 'value="'.$password.'"'; 
        if(isset($password_error)){
            if($passwordConfirm_error==true || $password_error==true)
                echo "autofocus";
            else{
                echo "autofocus";
            }
        }else{
            echo "autofocus";
        } 
    ?> 
    required>
    <?php
        if(isset($password_error) && isset($password_text)){
            if($password_error){
                echo '<small id="passwordConfirmHelp" class="text-danger">'.$password_text.'</small>';
            }
        }
    ?>
    <label for="inputpasswordConfirm" class="sr-only">Passwort bestätigen</label>
    <input type="password" id="inputpasswordConfirm" class="form-control" placeholder="Passwort bestätigen" name="passwordConfirm" <?php if(isset($passwordConfirm)) echo 'value="'.$passwordConfirm.'"'; ?> required>
    <?php
        if(isset($passwordConfirm_error) && isset($passwordConfirm_text)){
            if($passwordConfirm_error){
                echo '<small id="passwordConfirmHelp" class="text-danger">'.$passwordConfirm_text.'</small>';
            }
        }
        
        if(isset($login)){
            if($login==true){
                echo '<p  class="text-success">E-Mail wurde gesendet. Bitte kontrollieren Sie Ihren Posteingang...</p>';
                header('Refresh: 2; URL=signin.php');
            }
        }
    ?>

    <!--    
    <div class="checkbox mb-3">
        <label>
            <input type="checkbox" value="remember-me"> Mbaj mend
        </label>
    </div>
    -->
    <button class="btn btn-lg btn-dark btn-block" type="submit" name="reset">Reset</button>
    <p class="mt-5 mb-3 text-muted text-center">&copy; Infotainment System <?php echo date("Y");?></p>
</form>
