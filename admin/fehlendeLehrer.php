<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";
    $diff=0;
    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }
    
    $rawDate = date("Y-m-d");
    $day = date('N', strtotime($rawDate));
    $tage = 0;
    if($day==6){
        $day=1;
        $tage=2;
    }else if($day==7){
        $day=1;
        $tage=1;
    }
    if(isset($_GET['day'])){
        if($day < $_GET['day']){
            $diff = $_GET['day'] - $day; 
            $day = $_GET['day'];
            //$rawDate = date('Y-m-d', strtotime($rawDate . ' +'.$diff.' day'));
            //echo date('m/d/Y', strtotime($rawDate . ' +'.$diff.' day'));
        }else if($day == 5 && $_GET['day']<$day){
            //$diff = 3;
            $diff = 2+$_GET['day'];
            //$rawDate = date('Y-m-d', strtotime($rawDate . ' +'.$diff.' day'));
            //$day = 1;
            $day = $_GET['day'];
        }else if($_GET['day']<$day){
            $diff = $_GET['day']-$day;
            $day = $_GET['day'];
        }
    }
    $sql = "SELECT u.u_id as u_id, u.lehrer as lehrer
            FROM tb_infotainment_unterricht u
            left join (
                select u_id
                from tb_infotainment_unterricht
                where tag = :tag
                group by lehrer
            ) u1
            on u.u_id = u1.u_id
            left join tb_infotainment_fehlendelehrer l
            on u.u_id = l.u_id and woche = :woche
            where u.tag = :tag1 and u.lehrer <> ''and u1.u_id is not null and l.u_id is not null
            order by u.klasse asc ;";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":tag",$day);
    $stmt->bindValue(":woche",date('Y',strtotime($rawDate)).''.date('W',strtotime($rawDate . ' +'.($diff + $tage).' day')));
    $stmt->bindParam(":tag1",$day);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //foreach($result as $row) {  
    //    echo $row['u_id']." ".$row['unterricht_nr']." ".$row['klasse']." ".$row['lehrer']."<br>";
    //}
   
?>

<div class="container">
    <div class="row">
        <div class="col-md-3 mt-3">
        <h1>Supplierplan</h1>
        </div>
        <div class="form-group col-md-2 mt-4">
        <select name="day"class="form-control">
                <option value="1" <?php if($day==1){ echo 'selected';}?>>Montag</option>
                <option value="2" <?php if($day==2){ echo 'selected';}?>>Dienstag</option>
                <option value="3" <?php if($day==3){ echo 'selected';}?>>Mittwoch</option>
                <option value="4" <?php if($day==4){ echo 'selected';}?>>Donnerstag</option>
                <option value="5" <?php if($day==5){ echo 'selected';}?>>Freitag</option>
            </select>
        </div>
        <div class="col-md-2 mt-4">
            <h2><?php echo "".date('d-m-Y',strtotime($rawDate . ' +'.($diff+$tage).' day')); ?></h2>
        </div>
        <div class="col-md-3 mt-4">
            <h2><?php echo "Woche: ".date('W',strtotime($rawDate . ' +'.($diff+$tage).' day')); ?></h2>
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
                        <td>'.$row['lehrer'].'</td>
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