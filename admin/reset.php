<?php
	require_once "connection.php";
    require_once "header.php";
    //require_once "navigation.php";
    if(isset($_SESSION['loggedin'])){
        header('Location: index.php');
    }

    if(isset($_POST['login']) && $_SERVER["REQUEST_METHOD"] == "POST"){
        $emailConfirm_error = false;
        $email_error = false;

        // Provo nese emaili eshte bosh
        if(empty(trim($_POST["email"]))){
            $email_text = "Ju lutem shkruani emailin tuaj.";
            $email_error = true;
        } else{
            $email = trim($_POST["email"]);
        }
        
        // Provo nese emailConfirmi eshte bosh
        if(empty(trim($_POST["emailConfirm"]))){
            $emailConfirm_text = "Ju lutem konfirmoni emailin tuaj.";
            $emailConfirm_error = true;
        } else{
            $emailConfirm = trim($_POST["emailConfirm"]);
        }

        if($email == $emailConfirm){
            if(!$emailConfirm_error && !$email_error){
                $sql = "SELECT u_id,u_email,u_nickname FROM tb_infotainment_users WHERE u_email = :email";
                $stmt = $con->prepare($sql);
                //Bind value.
                $stmt->bindValue(':email', $email);
                //Execute.
                $stmt->execute();
                //Fetch row.
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if($user === false){
                    $emailConfirm_text = "Ky user nuk ekziston. Provo perseri.";
                    $emailConfirm_error = true;
                }else{
                    $insert = false;
                    $sql = "Select p_id FROM tb_infotainment_password_reset where u_id=:userid and timestampdiff(hour,expire,now())<0;";
                    $stmt = $con->prepare($sql);
                    $stmt->bindValue(':userid', $user['u_id']);
                    if($stmt->execute()){
                        if($stmt->rowCount()>0){
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $sql = "UPDATE tb_infotainment_password_reset set expire=DATE_SUB(now(),INTERVAL 5 DAY) where p_id=:pid;";
                            $stmt = $con->prepare($sql);
                            foreach($result as $row){
                                $stmt->bindValue(':pid', $row['p_id']);
                                if($stmt->execute()){
                                    $insert = true;
                                }
                            }
                        }else{
                            $insert = true;
                        }

                    }
                    if($insert == true)
                    $sql = "INSERT INTO tb_infotainment_password_reset(u_id) VALUES(:userid)";
                    $stmt = $con->prepare($sql);
                    //Bind value.
                    $stmt->bindValue(':userid', $user['u_id']);
                    //Execute.
                    if($stmt->execute()){
                        require ('reset_email.php');
                        //Recipients
                        $sql = "Select * FROM tb_infotainment_password_reset where u_id=:userid and timestampdiff(hour,expire,now())<0;";
                        $stmt = $con->prepare($sql);
                        $stmt->bindValue(':userid', $user['u_id']);
                        if($stmt->execute()){
                            $reset = $stmt->fetch(PDO::FETCH_ASSOC);
                            try {
                                
                                $mail->addAddress($user['u_email'], $user['u_nickname']);     // Add a recipient

                                // Content
                                $mail->isHTML(true);                                  // Set email format to HTML
                                $mail->Subject = 'Password Reset';
                                $mail->Body    = '<h3>Reset your password</h3><br><br>
                                Dear '.$user['u_nickname'].',</br></br>
                                You told us you forgot your password. If you really did, click <b><a href="http://localhost:4444/infotainment/infotainment/admin/reset_password.php?id='.$reset['u_id']."-".$reset['id'].'">here</a></b> 
                                to choose a new one.<br><br>
                                If you didn\'t mean to reset your password, then you can just ignore this email; your password will not change.<br><br>
                                This link will be available only during the next 24h.<br>
                                If u have any problems please contact the administrator.<br><br>
                                <b>Infotainment System</b>';
                                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                                $mail->send();
                                $login = true;
                                //echo 'Message has been sent';
                            } catch (Exception $e) {
                                $login = false;
                                //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                //echo "Message could not be sent.";
                            }
                        }
                        
                        //header("location: login.php");
                    }
                }
            }
        }else{
                $emailConfirm_text = "Emailet nuk perputhen.";
                $emailConfirm_error = true;
                $email_error = false;
        }
    }
?>

<link rel="stylesheet" href="css/signin.css">
<form class="form-signin" action="reset.php" method="POST">
    <img class="mb-4" src="img/logo_b.png" alt="" width="100%" height="auto">

    <h1 class="h3 mb-3 font-weight-normal text-center">Reseto fjalekalimin</h1>
    
    <label for="inputEmail" class="sr-only">Email address</label>
    <input type="email" id="inputEmail" class="form-control" placeholder="Email address" name="email" 
    <?php 
        if(isset($email))
            echo 'value="'.$email.'"'; 
        if(isset($email_error)){
            if($emailConfirm_error==true || $email_error==true)
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
        if(isset($email_error) && isset($email_text)){
            if($email_error){
                echo '<small id="emailConfirmHelp" class="text-danger">'.$email_text.'</small>';
            }
        }
    ?>
    <label for="inputEmailConfirm" class="sr-only">emailConfirm</label>
    <input type="email" id="inputEmailConfirm" class="form-control" placeholder="Konfirmo emailin" name="emailConfirm" <?php if(isset($emailConfirm)) echo 'value="'.$emailConfirm.'"'; ?> required>
    <?php
        if(isset($emailConfirm_error) && isset($emailConfirm_text)){
            if($emailConfirm_error){
                echo '<small id="emailConfirmHelp" class="text-danger">'.$emailConfirm_text.'</small>';
            }
        }

        
            echo '<p><small class="text-info"><a href="mailto:">Kontakto Administratorin</a></small><small class="text-info" style="float: right;"><a href="signin.php">Identifikohu</a></small></p>';
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
    <button class="btn btn-lg btn-dark btn-block" type="submit" name="login">Reset</button>
    <p class="mt-5 mb-3 text-muted text-center">&copy; Infotainment System <?php echo date("Y");?></p>
</form>
