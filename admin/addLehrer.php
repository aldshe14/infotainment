<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    $rawDate = date("Y-m-d");
    
    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }

        require_once "connection.php";
        $sql = "INSERT INTO  tb_infotainment_fehlendelehrer (u_id,woche) VALUES (:id,:woche);";
        $sth = $con->prepare($sql);
        $sth->bindParam(':id', $_GET["id"]);
        $sth->bindValue(":woche",date('Y',strtotime($rawDate)).''.date('W',strtotime($rawDate . ' +'.$_GET['d'].' day')));
        try {
            $sth->execute();
            $con = null;
            header('location:supplierplan.php?status=done&day='.$_GET['day']);
        } catch (PDOException $e) {
            header('location:supplierplan.php?status=err&day='.$_GET['day']);
        }
        exit();        
    ?>