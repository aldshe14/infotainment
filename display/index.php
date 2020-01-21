<?php
    require_once "php/connection.php";

    function getMac(){
        $mac = false;
        $arp = `arp -n`;
        $lines = explode("\n", $arp);
        //$mac = explode("\t", $lines[1]);
        $mac = explode(" ", $lines[1]);

        return $mac[20];
    }

    $MAC = getMac();

    $sql = "SELECT l.file
            FROM tb_infotainment_display d
            JOIN tb_infotainment_layout l
            ON d.layout_id = l.l_id
            where d.mac = :mac;
            ";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":mac",$MAC);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $layout = $result[0]['file'];
    //Only for test
    $layout = "layout1";
    
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
</html>
