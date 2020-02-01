<?php
    require_once "php/connection.php";
    require_once "php/functions.php";

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
