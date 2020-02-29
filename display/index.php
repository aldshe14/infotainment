<?php
    require_once "php/connection.php";
    require_once "php/functions.php";

    $MAC = getMac();
    //$MAC = "b8:27:eb:c1:e6:4e";
    
    $sql = "SELECT d.d_id,l.file
    FROM tb_infotainment_display d
    join tb_infotainment_layout l
    on d.layout_id = l.l_id
    where d.mac = :mac and l.name not like '-';
    ";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":mac",$MAC);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    if(!$result){
        header('location:welcome.php');
    }

    $reloadtime = null;
    $sql = "call sp_getLayout(:mac);";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":mac",$MAC);
    $stmt->execute();
    $result = $stmt->fetch();
    $stmt->closeCursor();
    if(!$result){
        $layout = "layout1";
        $reloadtime = 60000;
    }else{
        $layout = $result['layout'];
        $reloadtime = $result['reloadtime'] * 1000;
    }
    $sql = "SELECT l.name as name, ls.name as 'section', l.file as file
            FROM tb_infotainment_layout_sections ls 
            JOIN tb_infotainment_layout l
            ON l.l_id = ls.layout_id
            where l.file = :file";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":file",$layout);
    $stmt->execute();
    $sections= $stmt->fetchAll(PDO::FETCH_ASSOC);
    //require_once "php/".$layout.".php";
    
?>
<!DOCTYPE html>
<html>
<head>
	<title>Display - <?php echo $sections[0]['name']; ?></title>
	<link rel="stylesheet" href="css/<?php echo $sections[0]['file']; ?>.css">
	<script src="js/jquery.js"></script>
</head>
<body>
	<div class="grid-container">
        <?php 
           foreach($sections as $section){
               echo '<div class="'.$section['section'].'">';
               require_once('php/'.$section['section'].'.php');
               echo '</div>';
           }
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
