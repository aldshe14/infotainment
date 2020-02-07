<!DOCTYPE html>
<html lang="en">
<head>
	<title>
	<?php
		echo "Infotainment ";
		if(basename($_SERVER["SCRIPT_FILENAME"])=='index.php') {  
			echo '- Home';
		}else if(basename($_SERVER["SCRIPT_FILENAME"])=='supplierplan.php') {  
			echo '- Supplierplan';
		}else{
			echo '';
		}
	?>
	</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="description" content="">
    <meta name="author" content="Aldo Sheldija, Irena Bala">
    <meta name="generator" content="v1.0.0">

    <!-- Mobile viewport Optimierung -->
    
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href="img/favicon_1.png" type="image/x-icon"/>

	<!-- Timetables -->
  	<link rel="stylesheet" href="timetable/css/style.css">
	
	
  	<!-- Bootstrap CSS File-->
  	<link rel="stylesheet" href="css/bootstrap.min.css">

	
	<script src="js/jquery.min.js"></script>
	  
  	<!-- Bootstrap JS File-->
	<script src="js/jquery-3.3.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
   
    <script src="js/script.js"></script>
	<link href="fontawesome/css/all.css" rel="stylesheet"> <!--load all styles -->

    <!-- CSS File-->
    <link rel="stylesheet" href="css/main.css">
	
	<style>
	button a {
		color: white !important;
	}

	button a:hover{
		color: #fffff1 !important;
		text-decoration: none !important;
	}

	thead input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
	
	</style>


	<!-- DataTables-->

	<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
 
	<script type="text/javascript" src="DataTables/datatables.min.js"></script>

	<!-- CanvasJS -->
	<script type="text/javascript" src="js/canvasjs.min.js"></script>

	
</head>

<body>
