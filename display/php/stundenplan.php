<?php
	require_once "connection.php";
   

    $sql = "select * from vStundenplan
        where tag=:tag";

    $rawDate = date("Y-m-d");
    $day = date('N', strtotime($rawDate));
    if($day==6){
        $day=1;
    }
    elseif ($day==7) {
        $day=1;
    }
    else{
        $day=$day;
    }
    $stmt = $con->prepare($sql);
    $stmt->bindValue(":tag",$day);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql1= "select klasse from vStundenplan where klasse like '%%x'";
    $stmt1 = $con->prepare($sql1);
    $stmt1->execute();
    $res=$stmt1->fetchAll(PDO::FETCH_ASSOC);

    $sql2= "select klasse from vStundenplan where klasse like '%%y'";
    $stmt2 = $con->prepare($sql2);
    $stmt2->execute();
    $resi=$stmt2->fetchAll(PDO::FETCH_ASSOC);

    print_r($resi['']['klasse']);

echo '<table border = 1 style="width=100%"
<tr>
    <th>Gruppe1</th>
    <th> Gruppe2 </th>
    <th>Fach</th>
    <th>Lehrer</th>
    <th>Raum</th>

</tr>'
    ;

    foreach($result as $row) {
    
        echo "<tr>";  
     
            echo "<td>".$res['']['klasse']."</td> <td>".$resi['']['klasse']."</td><td>".$row['fach']."</td><td>".$row['lehrer']."</td><td>".$row['raum']."</td>";
       
            
       
        echo "</tr>";
    }
    echo "</table>";
?>
