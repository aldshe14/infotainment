<?php
	require_once "connection.php";
  
    $sql = "select * from vStundenplan
        where tag=:tag 
        and klasse not like '___'
        group by klasse";

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
    echo '<table border=1 style="width=100%">
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    <tr>
        <td>Klasse</td>
        <td colspan="3">1</td>
        <td colspan="3">2</td>
        <td colspan="3">3</td>
        <td colspan="3">4</td>
        <td colspan="3">5</td>
        <td colspan="3">6</td>
        <td colspan="3">7</td>
        <td colspan="3">8</td>
        <td colspan="3">9</td>
        <td colspan="3">10</td>
    </tr>';
    echo '<tr>
        <th></th>
        <th>Fach</th>
        <th>Lehrer</th>
        <th>Raum</th>
        <th>Fach</th>
        <th>Lehrer</th>
        <th>Raum</th>
        <th>Fach</th>
        <th>Lehrer</th>
        <th>Raum</th>
        <th>Fach</th>
        <th>Lehrer</th>
        <th>Raum</th>
        <th>Fach</th>
        <th>Lehrer</th>
        <th>Raum</th>
        <th>Fach</th>
        <th>Lehrer</th>
        <th>Raum</th>
        <th>Fach</th>
        <th>Lehrer</th>
        <th>Raum</th>
        <th>Fach</th>
        <th>Lehrer</th>
        <th>Raum</th>
        <th>Fach</th>
        <th>Lehrer</th>
        <th>Raum</th>
        <th>Fach</th>
        <th>Lehrer</th>
        <th>Raum</th>
    </tr>';
    foreach($result as $kl){
        $sql1= "select *, power(2,stunde -1) as bit from tb_infotainment_unterricht where tag= :tagg and klasse like :klassee group by stunde order by stunde ";
        $stmt1 = $con->prepare($sql1);
        $stmt1 ->bindValue(':tagg',$day);
        $stmt1 ->bindValue(':klassee',$kl['klasse'].'%');
        $stmt1->execute();
        $res=$stmt1->fetchAll(PDO::FETCH_ASSOC);
        echo '<tr><td rowspan="2">'.$kl['klasse'].'</td>'; 
        $gr = 0;
        for($i=0; $i<10; $i++) {
            if(isset($res[$i]['klasse'])){
                if(strlen($res[$i]['klasse'])>2){
                    $gr += $res[$i]['bit'];
                }

            }
        }
        for($i=0; $i<10; $i++) {

            if(!isset($res[$i]['klasse'])){
                echo '<td rowspan="2"></td><td rowspan="2"></td><td rowspan="2"></td>';
            }else{
                if(strlen($res[$i]['klasse'])>2){
                    echo '<td>'.$res[$i]['fach'].'</td><td>'.$res[$i]['klasse'].'</td><td>'.$res[$i]['raum'].'</td>';
                }else{
                    echo '<td rowspan="2">'.$res[$i]['fach'].'</td><td rowspan="2">'.$res[$i]['klasse'].'</td><td rowspan="2">'.$res[$i]['raum'].'</td>';
                }

            }
        }
        echo "</tr><tr>";
        for($i=0; $i<10; $i++) {
            if(isset($res[$i]['klasse'])){
                $sql1= "select * from tb_infotainment_unterricht where tag= :tagg and klasse like :klasse and stunde=:stunde";
                $stmt1 = $con->prepare($sql1);
                $stmt1 ->bindValue(':tagg',$day);
                $stmt1 ->bindValue(':klasse',$kl['klasse'].'%');
                $stmt1 ->bindValue(':stunde',$i+1);
                $stmt1->execute();
                $res1=$stmt1->fetchAll(PDO::FETCH_ASSOC);
                echo " ".($i+1);
                if(isset($res1[1])){
                    echo '<td>'.$res1[1]['fach'].'</td><td>'.$res1[1]['klasse'].'</td><td>'.$res1[1]['raum'].'</td>';
                }
                
            }
        }
        
        echo "</tr>";
 
    }
    echo "</table>";

?>