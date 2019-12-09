<?php
	require_once "connection.php";

    $sql = "SELECT * 
    FROM  tb_infotainment_chatbot_images
    limit 1 ;";
    $pdo = $con->prepare($sql);

    try {
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row){
                echo '<img src="data:image/jpeg;base64,'.base64_encode($row['image']) .'" />';

        }
    }catch (PDOException $e) {

    }
   

?>

