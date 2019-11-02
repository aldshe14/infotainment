<?php
	require_once "connection.php";

    $sql = "SELECT * FROM tb_infotainment_unterricht;";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table>";
    foreach($result as $row) {
        echo "<tr>";  
        echo "<td>".$row['u_id']."</td><td>".$row['unterricht_nr']."</td><td>".$row['klasse']."</td><td>".$row['lehrer']."</td>";
        echo "</tr>";
    }
    echo "</table>";
?>