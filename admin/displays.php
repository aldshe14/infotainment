<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }

    $sql = "SELECT * 
    FROM tb_infotainment_display ;";
    $pdo = $con->prepare($sql);
    $pdo->execute();
    $result = $pdo->fetchAll();
   
?>

<div class="container">
    <div class="row">
        <div class="col-md-3 mt-3">
        <h1>Displays</h1>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table id="resetuserdata" class="table table-striped table-bordered" data-order='[[ 0, "asc" ]]' data-page-length='50'>
            <thead>
                <tr>
                    <!--<th data-hide="true">ID</th>-->
                    <td>Lehrer</td>
                    <td>Edit</td>
                    <td>Löschen</td>
                </tr>
                <tr>
                    <!--<th data-hide="true">ID</th>-->
                    <th>Lehrer</th>
                    <th>Supplieren</th>
                    <th>Löschen</th>
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
                        <td>'.$row['d_id'].'</td>
                        <td><a href="editSupp.php?id='.$row['u_id'].'&d='.($diff+$tage).'&day='.$day.'">Supplieren</td>
                        <td><a href="delLehrer.php?id='.$row['u_id'].'&d='.($diff+$tage).'&day='.$day.'">Delete</td>';
                }
                echo '</tbody>
                    ';
            ?>
            <tfoot>
                <tr>
                    <!--<th data-hide="true">ID</th>-->
                    <th>Lehrer</th>
                    <th>Edit</th>
                    <th>Löschen</th>
                </tr>
            </tfoot>
        </table>
    </div>

<?php
	require_once "footer.php";
?>