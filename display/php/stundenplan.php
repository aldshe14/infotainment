<?php
	require_once "connection.php";
    
    $rawDate = date("Y-m-d");
    $day = date('N', strtotime($rawDate));

    $sql = "select * from vStundenplan
        where tag=:tag";
    $stmt = $con->prepare($sql);
    $stmt->bindValue(":tag",$day);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
  <table> <caption>Life Expectancy By Current Age</caption> <tr> <th colspan="2">65</th> <th colspan="2">40</th> <th colspan="2">20</th> </tr> <tr> <th>Men</th> <th>Women</th> <th>Men</th> <th>Women</th> <th>Men</th> <th>Women</th> </tr> <tr> <td>82</td> <td>85</td> <td>78</td> <td>82</td> <td>77</td> <td>81</td> </tr> </table>

Read more: https://html.com/tables/rowspan-colspan/#ixzz68IrG9LMg

echo '<table border = 1 style="width=100%"
<tr>
    <th colspan = "2">Klasse</th>

    <th>Fach</th>
    <th>Lehrer</th>
    <th>Raum</th>

</tr>'
    ;

  

    foreach($result as $row) {
    
        echo "<tr>";  
     
            echo "<td>".$row['klasse']."</td> <td>".$row['fach']."</td><td>".$row['lehrer']."</td><td>".$row['raum']."</td>";
       
            
       
        echo "</tr>";
    }
    echo "</table>";
?>
