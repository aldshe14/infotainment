<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }

    $sql = "SELECT * 
    FROM tb_infotainment_users ;";
    $pdo = $con->prepare($sql);
    $pdo->execute();
    $result = $pdo->fetchAll();

    if(isset($_GET['insert']) ){
        if($_GET['insert']=='done'){
            echo "<div id='hide' class=\"alert alert-success \">";
            echo "<p>Vokabel wurde erfolgreich geloescht!</p>";
            echo "</div>";
        }else if ($_GET['insert']=='err'){
            echo "<div id='hide' class=\"alert alert-danger \">";
            echo "<p>Vokabel konnte nicht geloescht werden!</p>";
            echo "</div>";
            header('Refresh: 2; URL=users.php');
        } 
    }
    if(isset($_GET['update']) ){
        if($_GET['update']=='done'){
            echo "<div id='hide' class=\"alert alert-success \">";
            echo "<p>Vokabel wurde erfolgreich geaendert!</p>";
            echo "</div>";
        }else if ($_GET['update']=='err'){
            echo "<div id='hide' class=\"alert alert-danger \">";
            echo "<p>Vokabel konnte nicht geaendert werden!</p>";
            echo "</div>";
        }
    }
    
   
?>
<div class="container">
    <br>
    <div class="text-center">
    <button type="button" class="btn btn-primary btn-lg"><a href="addUser.php">Shto User</a></button>
    </div>
    <br><br>
    <div class="table-responsive">
    <table id="resetuserdata" class="table table-striped table-bordered" data-order='[[ 1, "asc" ]]' data-page-length='25'>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Date</th>
                </tr>
                <tr>
                    <td>Name</td>
                    <td>Email</td>
                    <td>Role</td>
                    <td>Date</td>
                </tr>
            </thead>
            <?php
                foreach($result as $row){
                    //<td>'.$row['u_id'].'</td>
                    echo '
                        <tr>
                            <td>'.$row['u_nickname'].'</td>
                            <td>'.$row['u_email'].'</td>';
                        if($row['u_role']==777)
                            echo '<td>Admin</td>';
                        else 
                            echo '<td>User</td>';

                    echo'
                            <td>'.$row['u_register'].'</td>
                        </tr>
                    ';
                }
            ?>
        </table>
    </div>
</div>

<?php
	require_once "footer.php";
?>