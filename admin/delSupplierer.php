<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }

        require_once "connection.php";
        $sql = "DELETE FROM tb_infotainment_supp_unter where s_id = :id";
        $sth = $con->prepare($sql);
        $sth->bindParam(':id', $_GET["id"]);

        try {
            $sth->execute();
            $sql = "DELETE FROM tb_infotainment_supplieren where s_id = :id";
            $sth = $con->prepare($sql);
            $sth->bindParam(':id', $_GET["id"]);
            $con = null;
            header('location:suppliertabelle.php?status=done&day='.$_GET['day']);
        } catch (PDOException $e) {
            header('location:suppliertabelle.php?status=err&day='.$_GET['day']);
        }
        exit();        
    ?>