<?php

$servername="localhost";

//$usr="root";
$usr="infotainment";
//$pswd="";
$pswd="1nf0tainment";
$dbname="infotainment_system";

$message;

$con;

try{

	$con= new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8",$usr,$pswd);

    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


}catch(PDOException $e){
    $servername="185.62.175.221:33066";
    $con= new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8",$usr,$pswd);

    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $message = "Connection failed:". $e->getMessage();

}

session_start();


header( 'Cache-Control: no-store, no-cache, must-revalidate' ); 
header( 'Cache-Control: post-check=0, pre-check=0', false ); 
header( 'Pragma: no-cache' ); 
header('Content-Type: text/html; charset=utf-8');


?>