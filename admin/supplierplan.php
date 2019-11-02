<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }
    
    $rawDate = date("m/d/Y");

    $day = date('N', strtotime($rawDate));

    $sql = "SELECT * FROM tb_infotainment_unterricht where tag = :test and fach <> 'SU' group by lehrer order by klasse asc ;";
    $stmt = $con->prepare($sql);
    $stmt->bindValue(":test",$day);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //foreach($result as $row) {  
    //    echo $row['u_id']." ".$row['unterricht_nr']." ".$row['klasse']." ".$row['lehrer']."<br>";
    //}
   
?>

<div class="container">
    <h1 class="mt-4">Supplierplan</h1>
    <br>
    <div class="table-responsive">
        <table id="resetuserdata" class="table table-striped table-bordered" data-order='[[ 0, "asc" ]]' data-page-length='50'>
            <thead>
                <tr>
                    <!--<th data-hide="true">ID</th>-->
                    <th>Stunde</th>
                    <th>Klasse</th>
                    <th>Lehrer</th>
                    <th>Fach</th>
                    <th>Raum</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <?php
                echo '<tbody>
                ';
                foreach($result as $row){

                        echo '<tr>';
                        //<td>'.$row['p_id'].'</td>
                        //echo '<td></td>';
                    echo '
                            
                        <td>'.$row['stunde'].'</td>
                        <td>'.$row['klasse'].'</td>
                        <td>'.$row['lehrer'].'</td>
                        <td>'.$row['fach'].'</td>
                        <td>'.$row['raum'].'</td>
                        <td><a href="editSupp.php?id='.$row['u_id'].'">Fehlt</td>';

                }
                echo '</tbody>
                    ';
            ?>
            <tfoot>
                <tr>
                    <!--<th data-hide="true">ID</th>-->
                    <th>Nr.</th>
                    <th>Emri</th>
                    <th>Mbiemri</th>
                    <th>Lloji i Dokumentit</th>
                    <th>Vendodhja ne arkive</th>
                    <th>Edit</th>
                </tr>
            </tfoot>
        </table>
    </div>



<?php
	require_once "footer.php";
?>