<?php
    require_once "php/connection.php";
    $layout = 1;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Layout 1</title>
    <link rel="stylesheet" href="css/layout<?php echo '1' ;?>.css">
    <script src="js/jquery.js"></script>
	<style>


	</style>
</head>
<body>
    <div class="grid-container">
        <?php
            if($layout==1){
                require_once "php/layout1.php";
            }
        ?>
    </div>

    
</body>
</html>
