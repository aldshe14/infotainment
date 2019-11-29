<?php
	require_once "connection.php";


    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }

    $rawDate = date("Y-m-d");

    $sql = "DELETE FROM tb_infotainment_display where d_id = :id";
    $sth = $con->prepare($sql);
    $sth->bindParam(':id', $_GET["id"]);
    try {
        $sth->execute();
        $con = null;
        header('location:displays.php?status=done');
    } catch (PDOException $e) {
        header('location:displays.php?status=err');
    }
    exit();        
?>