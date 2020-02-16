<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";
    require_once "functions.php";
    require_once "reset_email.php";

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['role']) && isset($_POST['nickname']) ){
 
        // Validate name
            // Prepare an insert statement
            $sql = "Insert into tb_infotainment_users(u_email,u_nickname,u_role,u_pswd) VALUES (:email,:nickname,:role,:pwd)";
             
            if($sth = $con->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $sth->bindParam(':email', $_POST["email"]);
                $sth->bindParam(':nickname', $_POST["nickname"]);
                $sth->bindParam(':role', $_POST["role"]);
                $passwd = generateStrongPassword();
                $sth->bindValue(':pwd', password_hash($passwd,PASSWORD_DEFAULT));
                try {
                    $sth->execute();
                    $mail->addAddress($_POST["email"], $_POST["nickname"]);     // Add a recipient

                    // Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'Customer Registration - InfotainmentSystem';
                    $mail->Body    = '<h3>Welcome to InfotainmentSystem '.$_POST['nickname'].'</h3><br><br>
                    Your new account</br></br>
                    
                    Email: '.$_POST['email'].'<br><br>
                    Password: '.$passwd.'<br>
                    Please use the above credentials to login.<br><br>
                    Click <b><a href="http://htl-server.com:88/infotainment/admin">here</a> to login</b> 
                    <br><br>
                    <b>Infotainment System</b>';
                    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                    $mail->send();
                    //header('Location: users.php?insert=done');
                    echo "<div id='hide' class=\"alert alert-success \">";
                    echo "<p>Useri u shtua me sukses!</p>";
                    echo "</div>";
                    echo "<script> setTimeout(function(){
                        window.location.href = 'users.php';
                     }, 4000);</script>";

                } catch (PDOException $e) {
                    echo "<div id='hide' class=\"alert alert-danger \">";
                    echo "<p>Ndodhi nje gabim ju lutem provoni perseri!</p>";
                    echo "</div>";
                    //header('Location: users.php?insert=err');
                    //echo '<script>window.location.href = "users.php?insert=err";</script>';
                }
            }
        
    }

?>

<div class="container">
        <h1 class="mt-4">Shto User</h1>
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group col-sm-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group col-sm-3">
                <label>Name</label>
                <input type="text" name="nickname" class="form-control" required>
            </div>
            <div class="dropdown col-sm-3">
            <label>Roli</label>
            <select class="form-control" name="role">
                <?php

                    $sql = "SELECT * from tb_infotainment_roles order by r_name;";

                    $sth = $con->prepare($sql);                    
	                $sth->execute();	                    
	                $result = $sth->fetchAll(PDO::FETCH_ASSOC);
					
				    foreach($result as $row) { 
                        
                            echo "<option value='" . $row['r_id'] . "'>";
                            echo $row['r_name'];
                            echo "</option>";
                            
				    }

                ?>
                </select>
            </div>
            <br>
            <div class="form-group col-sm-3">
                <button type="submit" class="btn btn-dark btn-lg" value="Submit">Shto User</button>
            </div>
        </form>
    </div>    


<?php
    require_once "footer.php";
?>