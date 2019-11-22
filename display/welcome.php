<?php
    require_once "php/connection.php";

    $MAC = exec('getmac'); 
    $MAC = strtok($MAC, ' '); 

    $sql = "SELECT mac
            FROM tb_infotainment_display
            where mac = :mac;
            ";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":mac",$MAC);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result != false){
        header('Location: index.php');
    }

?>

<html lang="en">
<head>
	<style>
		body{
			/* background: #556270; 
            background: -webkit-linear-gradient(to left, #556270 , #FF6B6B); 
            background: linear-gradient(to left, #556270 , #FF6B6B); */
            /*  background: #ddd6f3; 
            background: -webkit-linear-gradient(to left, #ddd6f3 , #faaca8); 
            background: linear-gradient(to left, #ddd6f3 , #faaca8); */
            background: #292929;
            background: -webkit-linear-gradient(to left, #858585 , #292929); 
            background: linear-gradient(to left,#858585, #292929);
        	color:  #fff;
        	font-family: sans-serif;
		}
		.welcome-section{
			display: -webkit-flex;
			display: flex;
			-webkit-align-items:  center;
		  	align-items: center;
		  	-webkit-justify-content:  center;
		  	justify-content: center;
			
		  	height: 50%;
		  	font-size:  5em;
		  	border: none;
			margin: 0px;
			padding: 0px;
		}
		.player-details-section{
			display: inline-block;
			border: none;
			margin: 0px;
			padding: 0px;
			margin-left: 5%;
			/* font-family: Sans-serif; */
		}
		.player-id-section{
			font-size: 40px;
		}
		.separator{
			height: 10px;
			width: 100%;
			border: 0;
			box-shadow: 7px 10px 3px -10px #fff inset;
		}
		.player-ip-section{
			font-size: 15px;
		}
        img {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
	</style>
</head>
<body style="height: 720px;">
    <br>
    <img src="img/infotainment_white.png" alt="Infotainment" width="900vw"><br>
	<div class="welcome-section">
        <h3>Welcome!</h3>
	</div>
	<div class="player-details-section">
		<div class="player-id-section">
			Player ID : 
            <?php
                $MAC = exec('getmac'); 
                $MAC = strtok($MAC, ' '); 
                echo $MAC;
            ?>
		</div>
		<div class="separator"></div>
		<div class="player-ip-section">
			Infotainment System
		</div>
	</div>
</body>
</html>