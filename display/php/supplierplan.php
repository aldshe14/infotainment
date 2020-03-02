<?php
	//require_once "connection.php";
    $rawDate = date("Y-m-d");

    $sql = "SELECT u.stunde as stunde, u.fach as fach, u.lehrer as lehrer, u.raum as raum, 
            s.supplierer as supplierer, s.beschreibung as beschreibung, u.klasse as klasse
            FROM tb_infotainment_supplieren s
            join tb_infotainment_unterricht u
            on s.u_id = u.u_id 
            where u.tag = :tag and s.woche = :woche
            order by u.stunde asc;";
    $stmt = $con->prepare($sql);
    $stmt->bindValue(":tag",getDay());
    $stmt->bindValue(":woche",date('Y',strtotime($rawDate)).''.date('W',strtotime($rawDate)));
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<table border="1" width="97%" ID="Table2" cellpadding="0" cellspacing="0" style="margin-left:auto; margin-right:auto; margin-top:1%;"';
    echo '<thead><td>Stunde / Ora</td><td>Klasse / Klasa</td><td>Abwesend / Mungon</td><td>Supplierer / Zevendesuesi</td><td>Raum / Klasa</td><td>Text / Teksti</td></thead>';
    foreach($result as $row) {
        echo "<tr>";  
        echo "<td>".$row['stunde']."</td><td>".$row['klasse']."</td><td>".$row['lehrer']."</td><td>".$row['supplierer']."</td><td>".$row['raum']."</td><td>".$row['beschreibung']."</td>";
        echo "</tr>";
    }
    echo "</table>";
?>
