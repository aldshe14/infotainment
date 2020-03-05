<?php
	require_once "connection.php";
    $sql = "Select *
    from tb_infotainment_kalendarinfo;";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($result);
    $date = date('Y-m-d H:i');
    if($date>=$result['']['anzeigevon'] && $date<=$result['0']['anzeigebis']){
        echo '<table border="1" ID="Table2" cellpadding="0" cellspacing="0" style="margin-left:auto; margin-right:auto; margin-top:1%;"';
        echo '<thead><td>Title/Titulli</td><td>Beschreibung/Pershkrimi</td><td>Datum/data</td><td>Beginnt / Fillon</td><td>Endet / Mbaron</td></thead>';
        foreach($result as $row) {
            echo "<tr>";  
            echo "<td>".$row['title']."</td><td>".$row['beschreibung']."</td><td>".$row['datum']."</td><td>".$row['von']."</td><td>".$row['bis']."</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    else {
        echo "Keine Kalenderinformationen";
    }
   
?>
