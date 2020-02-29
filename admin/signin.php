<?php
	require_once "connection.php";
    require_once "header.php";
    //require_once "navigation.php";
    
    if(isset($_SESSION['loggedin'])){
        header('Location: index.php');
    }

    if(isset($_POST['login']) && $_SERVER["REQUEST_METHOD"] == "POST"){
        $password_error = false;
        $email_error = false;

        // Versuchen, ob die E-Mail Adresse leer ist
        if(empty(trim($_POST["email"]))){
            $email_text = "Bitte geben Sie Ihre E-Mail-Adresse ein.";
            $email_error = true;
        } else{
            $email = trim($_POST["email"]);
        }
        
        // Versuchen, ob das Passwort leer ist
        if(empty(trim($_POST["password"]))){
            $password_text = "Bitte geben Sie Ihr Passwort ein.";
            $password_error = true;
        } else{
            $password = trim($_POST["password"]);
        }

    
        if(!$password_error && !$email_error){
            $sql = "SELECT u_id, u_email, u_pswd, u_role FROM tb_infotainment_users WHERE u_email = :email";
            $stmt = $con->prepare($sql);
            //Bind value.
            $stmt->bindValue(':email', $email);
            //Execute.
            $stmt->execute();
            //Fetch row.
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            
            if($user === false){
                $email_text = "Dieser Benutzer existiert nicht.";
                $email_error = true;
                $password_error = false;
            }else{

                $validPassword = password_verify($password, $user['u_pswd']);
                
                if($validPassword){

                    
                    if(!password_verify('12345678', $user['u_pswd'])){
                        $_SESSION['user_id'] = $user['u_id'];
                        $_SESSION['loggedin'] = true;
                        $_SESSION['email'] = $user['u_email'];
                        $_SESSION['role'] = $user['u_role'];
                        header('Location: index.php');
                    }
                    else 
                        header('Location: ndrysho.php?id='.$user['u_id']);
                    exit;
                    
                } else if(strlen($password)<8 || strlen($password)>20){
                    $password_text = "Das Passwort muss aus (8-20) Zeichen bestehen.";
                    $password_error = true;
                    $email_error = false;
                }           
                else{
                    //$validPassword was FALSE. Passwords do not match.
                    $password_text = "Passwort ist falsch.";
                    $password_error = true;
                    $email_error = false;
                }
            }
        }
    }
?>

<link rel="stylesheet" href="css/signin.css">
<form class="form-signin" action="signin.php" method="POST">
    <img class="mb-4" src="img/logo_b.png" alt="" width="100%" height="auto">

    <h1 class="h3 mb-3 font-weight-normal text-center">Anmeldung</h1>
    
    <label for="inputEmail" class="sr-only">E-Mail-Addresse</label>
    <input type="email" id="inputEmail" class="form-control" placeholder="E-Mail-Addresse" name="email" 
    <?php 
        if(isset($email)) echo 'value="'.$email.'"'; 
        if(isset($password_error) && isset($email_error)){
            if($password_error==false || $email_error==true) 
            echo "autofocus";
        }else{
            echo "autofocus";
        } 
    ?> 
    
    required>
    <?php
        if(isset($email_error) && isset($email_text)){
            if($email_error){
                echo '<small id="passwordHelp" class="text-danger">'.$email_text.'</small>';
            }
        }
    ?>
    <label for="inputPassword" class="sr-only">Passwort</label>
    <input type="password" id="inputPassword" class="form-control" placeholder="Passwort" name="password" 
    <?php 
        if(isset($email_error) && isset($password_error)){
            if($password_error==true) 
                echo "autofocus"; 
        } 
    ?> 
    required>
    <?php
        if(isset($password_error) && isset($password_text)){
            if($password_error){
                echo '<small id="passwordHelp" class="text-danger">'.$password_text.'</small>';
            }
        }

        
            echo '<p><small class="text-info"><a href="reset.php">Passwort zurücksetzen</a></small></p>';
        
    ?>

    <!--    
    <div class="checkbox mb-3">
        <label>
            <input type="checkbox" value="remember-me"> Mbaj mend
        </label>
    </div>
    -->
    <button class="btn btn-lg btn-dark btn-block" type="submit" name="login">Sign in</button>
    <p class="mt-5 mb-3 text-muted text-center">&copy; Infotainment System <?php echo date("Y");?></p>
</form>
