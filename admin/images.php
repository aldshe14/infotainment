<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }

    $sql = "SELECT * 
    FROM  tb_infotainment_images;";
    $pdo = $con->prepare($sql);
    $pdo->execute();
    $result = $pdo->fetchAll();
   
?>

<div class="container">
    <div class="row">
        <div class="col-md-4 mt-3">
        <h1>Images</h1>
        </div>
        <div class="col-md-2 mt-3">
        <button type="button" class="btn btn-dark btn-lg"><a href="addImage.php">Add Image</a></button>
        </div>
        
    </div>
    <div class="text-center">
    
    </div>
    <br>
    <div class="table-responsive">
        <table id="resetuserdata" class="table table-striped table-bordered" data-order='[[ 0, "asc" ]]' data-page-length='50'>
            <thead>
                <tr>
                    <td>Name</td>
                    <td>Type</td>
                    <td>Delete</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
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
                        <td>'.$row['type'].'</td>
                        <td><a href="editImage.php?id='.$row['i_id'].'">Edit</a></td>
                        <td><a href="delImage.php?id='.$row['i_id'].'">Delete</a></td>';
                }
                echo '</tbody>
                    ';
            ?>
            <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Edit</th>
                    <td>Delete</td>
                </tr>
            </tfoot>
        </table>
    </div>

<?php
	require_once "footer.php";
?>