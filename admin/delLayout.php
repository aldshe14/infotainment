<?php
	require_once "connection.php";


    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }

    $sql = "DELETE FROM tb_infotainment_layout where l_id = :id";
    $sth = $con->prepare($sql);
    $sth->bindParam(':id', $_GET["id"]);
    try {
        $sth->execute();
        $con = null;
        header('location:layouts.php?status=done');
    } catch (PDOException $e) {
        //echo $e->getMessage();
       header('location:layouts.php?status=err');
    }
    exit();        
?>