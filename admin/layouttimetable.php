<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }

    $sql = "SELECT * 
    FROM  tb_infotainment_timetable_layout;";
    $pdo = $con->prepare($sql);
    $pdo->execute();
    $result = $pdo->fetchAll();
   
?>

<div class="container">
    <div class="row">
        <div class="col-md-4 mt-3">
        <h1>Layouts</h1>
        </div>
        <div class="col-md-2 mt-3">
        <button type="button" class="btn btn-dark btn-lg"><a href="addLayout.php">Add Layout</a></button>
        </div>
        
    </div>
    <div class="text-center">
    
    </div>
    <br>
    <div class="table-responsive">
        <table id="resetuserdata" class="table table-striped table-bordered" data-order='[[ 0, "asc" ]]' data-page-length='50'>
            <thead>
                <tr>
                    <td>Display</td>
                    <td>Layout</td>
                    <td>Von</td>
                    <td>Bis</td>
                    <td>Edit</td>
                    <td>Delete</td>
                </tr>
                <tr>
                    <th>Display</th>
                    <th>Layout</th>
                    <th>Von</th>
                    <th>Bis</th>
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
                        <td>'.$row['display_id'].'</td>
                        <td>'.$row['layout_id'].'</td>

                        <td>'.$row['von'].'</td>
                        <td>'.$row['bis'].'</td>
                        <td><a href="editLayout.php?id='.$row['l_id'].'">Edit</a></td>
                        <td><a href="delLayout.php?id='.$row['l_id'].'">Delete</a></td>';
                }
                echo '</tbody>
                    ';
            ?>
            <tfoot>
                <tr>
                <th>Display</th>
                    <th>Layout</th>
                    <th>Von</th>
                    <th>Bis</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </tfoot>
        </table>
    </div>

<?php
	require_once "footer.php";
?>