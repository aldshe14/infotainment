<?php
	require_once "connection.php";

    $sql = "SELECT * 
    FROM  tb_infotainment_website_posts
    order by datum desc limit 1 ;";
    $pdo = $con->prepare($sql);

    try {
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row){
            echo "<h5 style= 'margin:0px;'>".$row['title']."</h5>";
            //echo "<h5>".$row['content']."</h5>";
            //echo "".$row['datum']."";
            echo '<img src="data:image/jpeg;base64,'.base64_encode($row['image']) .'" width="70%" height=auto />';
        }
    }catch (PDOException $e) {

    }
   
?>

