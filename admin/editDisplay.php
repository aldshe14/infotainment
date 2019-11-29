<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name']) && isset($_POST['mac']) && isset($_POST['location']) && isset($_POST['layout']) ){
 
        // Validate name
            // Prepare an insert statement
            $sql = "UPDATE  tb_infotainment_display SET name=:name, mac=:mac, layout_id=:layout, location_id=:location where d_id=:id";
             
            if($sth = $con->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $sth->bindParam(':name', $_POST["name"]);
                $sth->bindParam(':mac', $_POST["mac"]);
                $sth->bindParam(':location', $_POST["location"]);
                $sth->bindParam(':layout', $_POST["layout"]);
                $sth->bindParam(':id', $_GET["id"]);
                try {
                    $sth->execute();
                    //header('Location: users.php?insert=done');
                    echo "<div id='hide' class=\"alert alert-success \">";
                    echo "<p>Display u ndryshua me sukses!</p>";
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
   
    $sql = "SELECT * FROM tb_infotainment_display WHERE d_id =" . $_GET["id"].";";
    $sth = $con->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container">
    <h1 class="mt-4">Edit Display</h1>
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?id='.$_GET['id']; ?>" method="post">
        <div class="form-row">
            <div class="form-group col-md-4">
            <label>Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo $result[0]['name']?>" required>
            </div>
            <div class="form-group col-md-4">
            <label>MAC</label>
            <input type="text" class="form-control" name="mac" value="<?php echo $result[0]['mac']?>" required>
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
                            if($result[0]['layout_id']==$row['l_id']){
                                echo '<option value="'.$row['l_id'].'" selected>'.$row['name'].'</option>
                                ';
                            }else{
                                echo '<option value="'.$row['l_id'].'">'.$row['name'].'</option>
                                ';
                            }
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
                            if($result[0]['location_id']==$row['l_id']){
                                echo '<option value="'.$row['l_id'].'" selected>'.$row['name'].'</option>
                                ';
                            }else{
                                echo '<option value="'.$row['l_id'].'">'.$row['name'].'</option>
                                ';
                            }
                        }
                    ?>
                </select>
            </div>
        </div>
        
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-dark btn-lg" value="Submit">Edit</button>
            </div>
        </form>
	</div>

<?php
	require_once "footer.php";
?>