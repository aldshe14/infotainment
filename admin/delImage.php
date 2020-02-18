<?php
	require_once "connection.php";


    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }

    $rawDate = date("Y-m-d");

    $sql = "DELETE FROM tb_infotainment_images where i_id = :id";
    $sth = $con->prepare($sql);
    $sth->bindParam(':id', $_GET["id"]);
    try {
        $sth->execute();
        $con = null;
        header('location:images.php?status=done');
    } catch (PDOException $e) {
        header('location:images.php?status=err');
    }
    exit();        
?>