<?php
	require_once "connection.php";
    
    $sql = "SELECT * FROM tb_infotainment_supplieren where datum=:date order by stunde asc;";
    $stmt = $con->prepare($sql);
    $stmt->bindValue(":date",date("Y-m-d"));

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<table border="1" width="97%" ID="Table2" cellpadding="0" cellspacing="0" height="97%" style="margin-left:auto; margin-right:auto; margin-top:1%;"';
    echo '<thead><td>Stunde</td><td>Klasse</td><td>Lehrer</td><td>Supplierer</td><td>Raum</td><td>Beschreibung</td></thead>';
    foreach($result as $row) {
        echo "<tr>";  
        echo "<td>".$row['stunde']."</td><td>".$row['klasse']."</td><td>".$row['lehrer']."</td><td>".$row['supplierer']."</td><td>".$row['raum']."</td><td>".$row['beschreibung']."</td>";
        echo "</tr>";
    }
    echo "</table>";
?>