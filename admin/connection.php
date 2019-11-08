<?php

$servername="htl-server.com:33066";

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
header('Content-Type: text/html; charset=utf-8');


?>