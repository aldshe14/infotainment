<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";


    $sql = "SELECT *,timestampdiff(minute,p.expire,now()) as exp
    FROM  tb_infotainment_password_reset p
    join tb_infotainment_users u
    on u.u_id=p.u_id;";
    $pdo = $con->prepare($sql);
    $pdo->execute();
    $result = $pdo->fetchAll();
   
?>

<div class="container">
    <div class="row">
        <div class="col-md-4 mt-3">
        <h1>Password Reset</h1>
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
                    <td>Email</td>
                    <td>Datum</td>
                    <td>Expired</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Datum</th>
                    <th>Expired</th>
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
                        <td>'.$row['u_nickname'].'</td>
                        <td>'.$row['u_email'].'</td>
                        <td>'.$row['datum'].'</td>
                        <td>';
                    if($row['exp']>=0)
                        echo "Yes";
                    else
                        echo "No";
                
                }
                echo '</tbody>
                    ';
            ?>
            <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Datum</th>
                    <th>Expired</th>
                </tr>
            </tfoot>
        </table>
    </div>

<?php
	require_once "footer.php";
?>