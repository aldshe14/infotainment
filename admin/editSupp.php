<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['numri']) && isset($_POST['data']) && isset($_POST['presidenti'])){
 
        // Validate name
            // Prepare an insert statement
            $sql = "UPDATE`tb_pres_dekret` set `d_numri`=:numri, `d_data`=:data, `d_presidenti`=:presidenti
                    WHERE d_numri = :id";
             
            if($sth = $con->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $sth->bindParam(':numri', $_POST["numri"]);
                $sth->bindParam(':data', $_POST["data"]);
                $sth->bindParam(':presidenti', $_POST["presidenti"]);
                $sth->bindParam(':id', $_GET["id"]);

                try {
                    $sth->execute();
                    //header('Location: users.php?insert=done');
                    echo "<div id='hide' class=\"alert alert-success \">";
                    echo "<p>Dekreti u ndryshua me sukses!</p>";
                    echo "</div>";
                    echo "<script> setTimeout(function(){
                        window.location.href = 'dekret.php';
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

    $sql = "SELECT * FROM tb_infotainment_unterricht WHERE u_id =" . $_GET["id"].";";
    $sth = $con->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    if($result[0]['tag']==1){
        $dayName = "Montag";
    } else if($result[0]['tag']==2){
        $dayName = "Dienstag";
    } else if($result[0]['tag']==3){
        $dayName = "Mittwoch";
    } else if($result[0]['tag']==4){
        $dayName = "Donnerstag";
    } else if($result[0]['tag']==5){
        $dayName = "Freitag";
    }
?>

<div class="container">
        <h1 class="mt-4">Stundenplan für <?php echo $result[0]['lehrer']." - ".$dayName; ?></h1>
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?id='.$_GET['id']; ?>" method="post">
        <div class="form-row">
            <div class="form-group col-md-4">
            <label>Emri</label>
            <input type="number" class="form-control" name="numri" value="<?php echo $result[0]['lehrer']?>" required>
            </div>
            <div class="form-group col-md-4">
            <label>Data</label>
            <input type="date" class="form-control" name="data" value="<?php echo $result[0]['u_id']?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-8">
                <label>Presidenti</label>
                <select name="presidenti" class="form-control">
                    <?php
                    $sql = "SELECT *
                    FROM tb_pres_presidenti order by p_mbarimi desc;";
                    $pdo = $con->prepare($sql);
                    $pdo->execute();
                    $result1 = $pdo->fetchAll();

                    foreach($result1 as $row){
                        if($row['p_id'] == $result[0]['d_presidenti'] ){
                            echo '<option value="'.$row['p_id'].'" selected>'.$row['p_emer'].' '.$row['p_mbiemri'].'</option>
                            ';
                        }else{
                            echo '<option value="'.$row['p_id'].'">'.$row['p_emer'].' '.$row['p_mbiemri'].'</option>
                            ';
                        }
                    }
                    ?>
                </select>
            </div>

            </div>
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg" value="Submit">Ndrysho</button>
            </div>
        </form>
    </div>    


<?php
    require_once "footer.php";
?>