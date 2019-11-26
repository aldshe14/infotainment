<?php
	require_once "connection.php";

    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }

    $sql = "DELETE FROM tb_infotainment_supplieren where u_id = :id and woche = :woche";
    $sth = $con->prepare($sql);
    $sth->bindParam(':id', $_GET["id"]);
    $sth->bindParam(':woche', $_GET["woche"]);
    try {
        $sth->execute();
        $con = null;
        header('location:suppliertabelle.php?status=done&day='.$_GET['day']);
    } catch (PDOException $e) {
        header('location:suppliertabelle.php?status=err&day='.$_GET['day']);
    }
    exit();        
    ?>