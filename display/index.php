<?php
    require_once "php/connection.php";
    require_once "php/functions.php";

    $MAC = getMac();
    //$MAC = "b8:27:eb:c1:e6:4e";
    $sql = "SELECT d_id,file
    FROM tb_infotainment_display d
    join tb_infotainment_layout l
    on d.layout_id = l.l_id
    where mac = :mac and l.name not like '-';
    ";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":mac",$MAC);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$result){
        !header('location:welcome.php');
    }


    $sql = "call sp_getLayout(:mac);";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":mac",$MAC);
    $stmt->execute();
    $result = $stmt->fetch();

    if(!$result){
        $layout = "layout1";
        $reloadtime = 60000;
    }else{
        $layout = $result['layout'];
        $reloadtime = $result['reloadtime'] * 1000;
    }
    
    //Only for test
    //$layout = "layout1";
    
?>

<!DOCTYPE html>
<html>
<head>
	<title>Layout 1</title>
    <link rel="stylesheet" href="css/<?php echo $layout; ?>.css">
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
            require_once "php/".$layout.".php";
        ?>
    </div>
</body>
<script>
			
		$(document).ready(function() {
			setTimeout(function() {
				window.location.reload(1);
			}, <?php echo $reloadtime;?>);
		});

	</script>
</html>
