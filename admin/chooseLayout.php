<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";


    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['layout']) && isset($_POST['von']) && isset($_POST['bis']) 
    && (isset($_POST['mon']) || isset($_POST['tue']) || isset($_POST['wed']) || isset($_POST['thu']) || isset($_POST['fri']) 
    || isset($_POST['sat']) || isset($_POST['sun']) ) ){
            
            $day = 0;

            if(isset($_POST['mon']))
                $day+=2;
            if(isset($_POST['tue']))
                $day+=4;
            if(isset($_POST['wed']))
                $day+=8;
            if(isset($_POST['thu']))
                $day+=16;
            if(isset($_POST['fri']))
                $day+=32;
            if(isset($_POST['sat']))
                $day+=64;
            if(isset($_POST['sun']))
                $day+=1;

        // Validate name
            // Prepare an insert statement
            $sql = "call sp_insertTimetableLayout(:display,:layout,:von, :bis, :day, @out);";
             
            if($sth = $con->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $sth->bindParam(':display', $_GET["did"]);
                $sth->bindParam(':layout', $_POST["layout"]);
                $sth->bindParam(':von', $_POST["von"]);
                $sth->bindParam(':bis', $_POST["bis"]);
                $sth->bindValue(':day', $day);
                try {
                    $sth->execute();
                    $sth->closeCursor();
                    $query = "SELECT @out as id;";
                    $stmt = $con->prepare($query);
                    $stmt->execute();
                    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $id = $res[0]['id'];
                    //header('Location: users.php?insert=done');
                    echo "<div id='hide' class=\"alert alert-success \">";
                    echo "<p>Kalendarinfo u shtua me sukses!</p>";
                    echo "</div>";
                    echo "<script> setTimeout(function(){
                        window.location.href = 'viewLayout.php?id=".$id."';
                     }, 4000);</script>";
                    
                } catch (PDOException $e) {
                    echo "<div id='hide' class=\"alert alert-danger \">";
                    echo "<p>Ndodhi nje gabim ju lutem provoni perseri!".$e->getMessage()."</p>";
                    echo "</div>";
                    //header('Location: users.php?insert=err');
                    //echo '<script>window.location.href = "users.php?insert=err";</script>';
                }
            }
        
    }

?>

<div class="container">
        <h1 class="mt-4">Layout</h1>
        <br>
        
        <form action="<?php echo "chooseLayout.php?did=".$_GET['did']; ?>" method="post">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Layout</label>
                    <select name="layout" class="form-control">
                        <?php
                            $sql = "SELECT l_id,name
                            FROM tb_infotainment_layout
                            where name not like '-';";
                            $pdo = $con->prepare($sql);
                            $pdo->execute();
                            $layout = $pdo->fetchAll();

                            foreach($layout as $row){
                                
                                //echo '<option data-thumbnail="data:image/jpeg;base64,'.base64_encode($row['icon']).'" value="'.$row['l_id'].'"><div>'.$row['name'].'</option>
                                //';
                                echo '<option value="'.$row['l_id'].'"><div>'.$row['name'].'</option>
                                ';
                                
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-sm-3">
                    <label>Von</label>
                    <input type="time" name="von" class="form-control" required>
                </div>
                <div class="form-group col-sm-3">
                    <label>Bis</label>
                    <input type="time" name="bis" class="form-control" required>
                        </div>
            </div>
            <div class="form-row">
            <div class="form-group col-sm-8">
                    <label>Repeat</label><br>
                    <div class="form-check form-check-inline">
                        <br><br>
                        <input class="form-check-input" type="checkbox" name="mon">
                        <label class="form-check-label">Mon</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <br><br>
                        <input class="form-check-input" type="checkbox" name="tue">
                        <label class="form-check-label">Tue</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <br><br>
                        <input class="form-check-input" type="checkbox" name="wed">
                        <label class="form-check-label">Wed</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <br><br>
                        <input class="form-check-input" type="checkbox" name="thu">
                        <label class="form-check-label">Thu</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <br><br>
                        <input class="form-check-input" type="checkbox" name="fri">
                        <label class="form-check-label">Fri</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <br><br>
                        <input class="form-check-input" type="checkbox" name="sat">
                        <label class="form-check-label">Sat</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <br><br>
                        <input class="form-check-input" type="checkbox" name="sun">
                        <label class="form-check-label">Sun</label>
                    </div>
                    
                </div>
                <input type="hidden" name="did" value="<?php echo $_GET['did']; ?>">
            </div>
            <div class="form-row">
            <div class="form-group">
                <button type="submit" class="btn btn-dark btn-lg" value="Submit">Ndrysho</button>
            </div>
            </div>
        </form>
    </div>
    
    
<?php
    require_once "timetableLayout.php";
    require_once "footer.php";
?>