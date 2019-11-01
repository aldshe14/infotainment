<?php
	require_once "connection.php";

    $sql = "SELECT * FROM tb_infotainment_unterricht;";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($result as $row) {  
        echo $row['u_id']." ".$row['unterricht_nr']." ".$row['klasse']." ".$row['lehrer']."<br>";
    }
   
?>