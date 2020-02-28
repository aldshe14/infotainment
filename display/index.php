<?php
    require_once "php/connection.php";
    require_once "php/functions.php";

    $MAC = getMac();
    //$MAC = "";
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

    if(!$result){
        header('location:welcome.php');
    }

    $reloadtime = null;
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
    

    require_once "php/".$layout.".php";
?>

    <script>	
        $(document).ready(function() {
            setTimeout(function() {
                window.location.reload(1);
            }, <?php echo $reloadtime;?>);
        });
    </script>
</html>
