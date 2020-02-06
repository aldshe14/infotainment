<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name']) && isset($_POST['mac']) && isset($_POST['location']) && isset($_POST['layout']) ){
 
        // Validate name
            // Prepare an insert statement
            $sql = "Insert into tb_infotainment_display(name,mac,layout_id,location_id) Values(:name, :mac, :layout, :location);";
             
            if($sth = $con->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $sth->bindParam(':name', $_POST["name"]);
                $mac = str_replace(array(':','.','-'), '-',$_POST["mac"]);
                $sth->bindParam(':mac', $mac);                
                $sth->bindParam(':location', $_POST["location"]);
                $sth->bindParam(':layout', $_POST["layout"]);
                try {
                    $sth->execute();
                    //header('Location: users.php?insert=done');
                    echo "<div id='hide' class=\"alert alert-success \">";
                    echo "<p>Display u shtu me sukses!</p>";
                    echo "</div>";
                    echo "<script> setTimeout(function(){
                        window.location.href = 'displays.php';
                     }, 2000);</script>";

                } catch (PDOException $e) {
                    echo "<div id='hide' class=\"alert alert-danger \">";
                    echo "<p>Ndodhi nje gabim ju lutem provoni perseri!</p>";
                    echo "</div>";
                    //header('Location: users.php?insert=err');
                    //echo '<script>window.location.href = "users.php?insert=err";</script>';
                }
            }
        
    }

?>

<div class="container">
    <h1 class="mt-4">Add Display</h1>
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-row">
            <div class="form-group col-md-4">
            <label>Name</label>
            <input type="text" class="form-control" name="name" value="" required>
            </div>
            <div class="form-group col-md-4">
            <label>MAC</label>
            <input type="text" class="form-control" name="mac" value="" placeholder="ac-de-da-7y-fe-s2" pattern="([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$"  required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Layout</label>
                <select name="layout" class="form-control">
                    <?php
                        $sql = "SELECT *
                        FROM tb_infotainment_layout;";
                        $pdo = $con->prepare($sql);
                        $pdo->execute();
                        $layout = $pdo->fetchAll();

                        foreach($layout as $row){
                            
                            echo '<option value="'.$row['l_id'].'">'.$row['name'].'</option>
                            ';
                            
                        }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Location</label>
                <select name="location" class="form-control">
                    <?php
                        $sql = "SELECT *
                        FROM tb_infotainment_location;";
                        $pdo = $con->prepare($sql);
                        $pdo->execute();
                        $location = $pdo->fetchAll();

                        foreach($location as $row){
                            
                            echo '<option value="'.$row['l_id'].'">'.$row['name'].'</option>
                            ';
                        
                        }
                    ?>
                </select>
            </div>
        </div>
        
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-dark btn-lg" value="Submit">Add</button>
            </div>
        </form>
	</div>

<?php
	require_once "footer.php";
?>