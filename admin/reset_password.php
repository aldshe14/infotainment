<?php
	require_once "connection.php";

    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }
    if(isset($_GET['id'])){     
        $sql = "UPDATE tb_pres_users set u_pswd= :pswd
        WHERE u_id = :id ;";
        $pdo = $con->prepare($sql);
        $pdo->bindParam(':pswd', password_hash('12345678',PASSWORD_DEFAULT));
        $pdo->bindParam(':id', $_GET['uid']);
        try {
            $pdo->execute();
            $query = "UPDATE tb_pres_reset set r_status = 200 WHERE r_id = :id ;";
            $stmt = $con->prepare($query);
            $stmt->bindParam(':id', $_GET['id']);
            $stmt->execute();
            $con = null;
            header('location:user_reset.php?status=done');
        } catch (PDOException $e) {
            header('location:user_reset.php?status=err');
        }
        exit();    
    }
?>