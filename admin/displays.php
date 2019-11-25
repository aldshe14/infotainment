<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }

    $sql = "SELECT * 
    FROM  vDisplays ;";
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
                    <td>Name</td>
                    <td>MAC</td>
                    <td>Layout</td>
                    <td>Location</td>
                    <td>Edit</td>
                    <td>Delete</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>MAC</th>
                    <th>Layout</th>
                    <th>Location</th>
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
                        <td>'.$row['name'].'</td>
                        <td>'.$row['mac'].'</td>
                        <td>'.$row['layout'].'</td>
                        <td>'.$row['location'].'</td>
                        <td><a href="editSupp.php?id='.$row['id'].'">Edit</a></td>
                        <td><a href="delLehrer.php?id='.$row['id'].'">Delete</a></td>';
                }
                echo '</tbody>
                    ';
            ?>
            <tfoot>
                <tr>
                    <th>Name</th>
                    <th>MAC</th>
                    <th>Layout</th>
                    <th>Location</th>
                    <th>Edit</th>
                    <td>Delete</td>
                </tr>
            </tfoot>
        </table>
    </div>

<?php
	require_once "footer.php";
?>