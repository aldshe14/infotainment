<?php
require_once "php/functions.php";

$servername="185.62.175.221:33066";
$usr="infotainment";
$pswd="1nf0tainment";
$dbname="infotainment_system";

$message;
$con;

try{
	$con= new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8",$usr,$pswd);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    $message = "Connection failed:". $e->getMessage();
}

session_start();
header( 'Cache-Control: no-store, no-cache, must-revalidate' ); 
header( 'Cache-Control: post-check=0, pre-check=0', false ); 
header( 'Pragma: no-cache' ); 

$IP = getIPAddress();
$MAC = getMac();
$sql = "SELECT ip
            FROM tb_infotainment_display
            where mac = :mac;
            ";
$stmt = $con->prepare($sql);
$stmt->bindParam(":mac",$MAC);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if($result[0]!="$IP"){
    $sql = "UPDATE tb_infotainment_display SET ip=:ip
            where mac = :mac;
            ";
$stmt = $con->prepare($sql);
$stmt->bindParam(":mac",$MAC);
$stmt->bindParam(":ip",$IP);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>