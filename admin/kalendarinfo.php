<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    $sql = "SELECT * 
    FROM  tb_infotainment_kalendarinfo;";
    $pdo = $con->prepare($sql);
    $pdo->execute();
    $result = $pdo->fetchAll();
   
?>

<div class="container">
    <div class="row">
        <div class="col-md-4 mt-3">
        <h1>Kalendarinfo</h1>
        </div>
        <div class="col-md-4 mt-3">
        <button type="button" class="btn btn-dark btn-lg"><a href="addKalendarinfo.php">Kalendarinfo hinzufügen</a></button>
        </div>
        
    </div>
    <div class="text-center">
    
    </div>
    <br>
    <div class="table-responsive">
        <table id="resetuserdata" class="table table-striped table-bordered" data-order='[[ 0, "asc" ]]' data-page-length='50'>
            <thead>
                <tr>
                    <td>Title</td>
                    <td>Beschreibung</td>
                    <td>Datum</td>
                    <td>Edit</td>
                    <td>Delete</td>
                </tr>
                <tr>
                    <th>Title</th>
                    <th>Beschreibung</th>
                    <th>Datum</th>
                    <th>Edit</th>
                    <th>Delete</th>
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
                        <td>'.$row['title'].'</td>
                        <td>'.$row['beschreibung'].'</td>
                        <td>'.$row['datum'].'</td>
                        <td><a href="editKalendarinfo.php?id='.$row['k_id'].'">Edit</a></td>
                        <td><a href="delKalendarinfo.php?id='.$row['k_id'].'">Delete</a></td>';
                }
                echo '</tbody>
                    ';
            ?>
            <tfoot>
                <tr>
                    <th>Title</th>
                    <th>Beschreibung</th>
                    <th>Datum</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </tfoot>
        </table>
    </div>

<?php
	require_once "footer.php";
?>