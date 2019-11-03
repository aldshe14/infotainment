<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }
    
    $rawDate = date("m/d/Y");

    $day = date('N', strtotime($rawDate));
    if($day>5)
        $day=1;

    if(isset($_GET['day']))
        $day = $_GET['day'];

    $sql = "SELECT * FROM tb_infotainment_unterricht where tag = :test and fach <> 'SU' and lehrer <> '' group by lehrer order by klasse asc ;";
    $stmt = $con->prepare($sql);
    $stmt->bindValue(":test",$day);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //foreach($result as $row) {  
    //    echo $row['u_id']." ".$row['unterricht_nr']." ".$row['klasse']." ".$row['lehrer']."<br>";
    //}
   
?>

<div class="container">
    <div class="row">
        <div class="col-md-4 mt-4">
        <h1>Supplierplan</h1>
        </div>
        <div class="form-group col-md-4 mt-4">
            <select name="day" class="form-control">
                <option value="1" <?php if(isset($_GET['day'])){if($_GET['day']==1){ echo 'selected'; $selected = 1;}}else{ echo 'selected';$selected = 1;}?>>Montag</option>
                <option value="2" <?php if(isset($_GET['day'])){if($_GET['day']==2){ echo 'selected'; $selected = 2;}}?>>Dienstag</option>
                <option value="3" <?php if(isset($_GET['day'])){if($_GET['day']==3){ echo 'selected'; $selected = 3;}}?>>Mittwoch</option>
                <option value="4" <?php if(isset($_GET['day'])){if($_GET['day']==4){ echo 'selected'; $selected = 4;}}?>>Donnerstag</option>
                <option value="5" <?php if(isset($_GET['day'])){if($_GET['day']==5){ echo 'selected'; $selected =5;}}?>>Freitag</option>
            </select>
            
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table id="resetuserdata" class="table table-striped table-bordered" data-order='[[ 0, "asc" ]]' data-page-length='50'>
            <thead>
                <tr>
                    <!--<th data-hide="true">ID</th>-->
                    <th>Lehrer</th>
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
                        <td>'.$row['lehrer'].'</td>
                        <td><a href="editSupp.php?id='.$row['u_id'].'">Fehlt</td>';

                }
                echo '</tbody>
                    ';
            ?>
            <tfoot>
                <tr>
                    <!--<th data-hide="true">ID</th>-->
                    <th>Lehrer</th>
                    <th>Edit</th>
                </tr>
            </tfoot>
        </table>
    </div>

<script type="text/javascript">
        (function () {
            var sel;

            $("select[name=day]").focus(function () {
                // Store the current value on focus, before it changes
                sel = this.value;
            }).change(function() {
                // Do soomething with the previous value after the change
                sel = this.value;
                window.location.href = '?day='+sel;

            });
        })();
        
        $(document).ready(function(){
        $(".test").click(function(){
                $(this).next().show();
                $(this).next().hide();
            });

        });
        
    </script>

<?php
	require_once "footer.php";
?>