<?php
	require_once "connection.php";
    $sql = "select * 
    from vKlasse as v
    where v.raum not in(select u.raum from tb_infotainment_unterricht u
                        where u.stunde=:st and u.tag=:dayy);
                        ;";
    $rawDate = date("Y-m-d");
    $day = date('N', strtotime($rawDate));
    $st = date('H:i');
    $hour="";
    if ($day==6) {
        $day=1;
    }
    elseif ($day==7) {
        $day=1;
    }
    else{
        $day=$day;
    }
    $sql1 = "select * from tb_infotainment_stunden;";
    $stmt1= $con-> prepare($sql1);
    $stmt1->execute();
    $res = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    //print_r($res);
    
        if ($st >= $res['0']['beginn'] && $st<= $res['0']['ende']) {
            $hour=1;
        
        }
        elseif ($st>=$res['1']['beginn'] && $st<= $res['1']['ende']) {
            $hour=2;        
    }
        elseif ($st>=$res['2']['beginn'] && $st<= $res['2']['ende']) {
            $hour=3;

    }
        elseif ($st>=$res['3']['beginn'] && $st<= $res['3']['ende']) {
            $hour=4; 
        }
        elseif ($st>=$res['4']['beginn'] && $st<= $res['4']['ende']) {
            $hour=5;
        }
        elseif ($st>=$res['5']['beginn'] && $st<= $res['5']['ende']) {
            $hour=6;
        }
        elseif ($st>=$res['6']['beginn'] && $st<= $res['6']['ende']) {
            $hour=7;
        }
        elseif ($st>=$res['7']['beginn'] && $st<= $res['7']['ende']) {
            $hour=8;
        }
        elseif ($st>=$res['8']['beginn'] && $st<= $res['8']['ende']) {
            $hour=9;
        }
        elseif ($st>=$res['9']['beginn'] && $st<= $res['9']['ende']) {
            $hour=10;
        }
        else{
            echo "Alle Klassen sind leer";
        }

    $stmt = $con->prepare($sql);
    $stmt->bindValue(":st",$hour);
    $stmt->bindValue(":dayy",$day);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $last_key = $result[sizeof($result)-1]['raum'];
     //echo '<table border="1" ID="Table2" cellpadding="0" cellspacing="0" style="margin-left:auto; margin-right:auto; margin-top:1%;"';
     //echo '<thead><td>Raum</td></thead>';
    foreach($result as $r) {
        //echo "<tr>";  
        //echo "<td>".$r['raum']."</td>";
        //echo "</tr>";
        if ($r['raum']==$last_key) {
            echo $r['raum'];
        }else{
            echo $r['raum'].', ';
        }
        
    }
    //echo "</table>";
?>