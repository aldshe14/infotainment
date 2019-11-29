<?php
	require_once "connection.php";


    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }

    $rawDate = date("Y-m-d");

    $sql = "DELETE FROM tb_infotainment_supplieren where 1";
    $sth = $con->prepare($sql);
    $sth->execute();

    $sql = "DELETE FROM tb_infotainment_fehlendelehrer where 1";
    $sth = $con->prepare($sql);
    $sth->execute();

    $sql = "DELETE FROM tb_infotainment_unterricht where 1";
    $sth = $con->prepare($sql);
    try {
        $sth->execute();
        $con = null;
        header('location:importStundenplan.php?status=done');
    } catch (PDOException $e) {
        header('location:importStundenplan.php?status=err');
    }
?>