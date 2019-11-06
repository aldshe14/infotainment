<?php
	require_once "connection.php";
    require_once "header.php";
    //require_once "navigation.php";
    

    if(isset($_POST['login']) && $_SERVER["REQUEST_METHOD"] == "POST"){
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
                    $stmt->bindValue(':id', $_GET['id']);
                    $stmt->execute();
                    
                    try {
                        $stmt->execute();
                        $query = "Select * from tb_infotainment_users where u_id = :id;";
                        $pdo = $con->prepare($query);
                        $pdo->bindValue(':id', $_GET['id']);
                        $pdo->execute();
                        $user = $pdo->fetch(PDO::FETCH_ASSOC);
                        $_SESSION['user_id'] = $user['u_id'];
                        $_SESSION['loggedin'] = true;
                        $_SESSION['email'] = $user['u_email'];
                        $_SESSION['role'] = $user['u_role'];
                        header('Location: index.php');
                        $login = true;
                    }catch (PDOException $e) {
                        $passwordConfirm_text = "Passwordi nuk u resetua. Provo perseri.";
                        $passwordConfirm_error = true;
                    }
                        exit();    
                }else if(strlen($password)<8 || strlen($password)>20){
                    $password_text = "Passwordi duhet te jete (8-20) karaktere.";
                    $password_error = true;
                    $email_error = false;
                }   
                else{
                    $passwordConfirm_text = "Passwordi duhet te jete i ndryshem nga 12345678";
                    $passwordConfirm_error = true;
                }
            }
        }else{
                $passwordConfirm_text = "Passwordet nuk perputhen.";
                $passwordConfirm_error = true;
                $password_error = false;
        }
    }
?>

<link rel="stylesheet" href="css/signin.css">
<form class="form-signin" action="ndrysho.php?id=<?php echo $_GET['id']; ?>" method="POST">
    <img class="mb-4" src="images/logo.png" alt="" width="100%" height="auto">

    <h1 class="h3 mb-3 font-weight-normal text-center">Ndrysho fjalekalimin</h1>
    
    <label for="inputpassword" class="sr-only">Password</label>
    <input type="password" id="inputpassword" class="form-control" placeholder="Password" name="password" 
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
    <label for="inputpasswordConfirm" class="sr-only">Konfirmo Passwordin</label>
    <input type="password" id="inputpasswordConfirm" class="form-control" placeholder="Konfirmo passwordin" name="passwordConfirm" <?php if(isset($passwordConfirm)) echo 'value="'.$passwordConfirm.'"'; ?> required>
    <?php
        if(isset($passwordConfirm_error) && isset($passwordConfirm_text)){
            if($passwordConfirm_error){
                echo '<small id="passwordConfirmHelp" class="text-danger">'.$passwordConfirm_text.'</small>';
            }
        }
        echo '<br><br>';
        if(isset($login)){
            if($login==true){
                echo '<p  class="text-success">Passwordi u resetua me sukses ...</p>';
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
    <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">Reset</button>
    <p class="mt-5 mb-3 text-muted text-center">&copy; Presidenca <?php echo date("Y");?></p>
</form>
