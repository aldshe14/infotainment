<?php
	require_once "connection.php";


    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }

    $rawDate = date("Y-m-d");

    $sql = "DELETE FROM tb_infotainment_fehlendelehrer where u_id = :id and woche = :woche";
    $sth = $con->prepare($sql);
    $sth->bindParam(':id', $_GET["id"]);
    $sth->bindValue(":woche",date('Y',strtotime($rawDate)).''.date('W',strtotime($rawDate . ' +'.$_GET['d'].' day')));
    try {
        $sth->execute();
        $con = null;
        header('location:fehlendeLehrer.php?status=done&day='.$_GET['day']);
    } catch (PDOException $e) {
        header('location:fehlendeLehrer.php?status=err&day='.$_GET['day']);
    }
    exit();        
?>