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

    

    $layout = 1;
    
?>
<!DOCTYPE html>
<html>
<head>
	<title>Layout 1</title>
    <link rel="stylesheet" href="css/layout<?php echo '1' ;?>.css">
    <script src="js/jquery.js"></script>
	<style>
@media print
{
  table { page-break-after:auto }
  tr    { page-break-inside:avoid; page-break-after:auto }
  td    { page-break-inside:avoid; page-break-after:auto }
  thead { display:table-header-group }
  tfoot { display:table-footer-group }
}
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
