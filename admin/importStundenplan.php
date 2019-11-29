<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['import'])){
        $file = $_FILES['import']['tmp_name'];
        $array = explode("\n", file_get_contents($file));
       // print_r($array);

        $status = true;
        
        foreach($array as $row){
            ini_set('max_execution_time', 300); //300 seconds = 5 minutes
            $result = explode(";", $row);
            if (!isset($result[1]))
                $result[1]='';
            else
            $result[1] = str_replace('"','',$result[1]);
            if (!isset($result[2]))
                $result[2]='';
            else
                $result[2] = str_replace('"','',$result[2]);
            if (!isset($result[3]))
                $result[3]='';
            else
                $result[3] = str_replace('"','',$result[3]);
            if (!isset($result[4]))
                $result[4]='';
            else
                $result[4] = str_replace('"','',$result[4]);
            if (!isset($result[5]))
                $result[5]='';
            if (!isset($result[6]))
                $result[6]='';
                
           //echo $result[1]." ".$result[2]." ".$result[3]." ".$result[4]." ".$result[5]." ".$result[6]."<br>";

           $sql = "Insert into tb_infotainment_unterricht(klasse,lehrer,fach,raum,tag,stunde) VALUES (:klasse,:lehrer,:fach,:raum,:tag,:stunde)";

           
            
            if($sth = $con->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $sth->bindParam(':klasse', $result[1]);
                $sth->bindParam(':lehrer', $result[2]);
                $sth->bindParam(':fach', $result[3]);
                $sth->bindValue(':raum', $result[4]);
                $sth->bindValue(':tag', $result[5],PDO::PARAM_INT);
                $sth->bindValue(':stunde', $result[6],PDO::PARAM_INT);
                try {
                    $sth->execute();
                    //header('Location: users.php?insert=done');
                    

                } catch (PDOException $e) {
                    $status = false;
                    //header('Location: users.php?insert=err');
                    //echo '<script>window.location.href = "users.php?insert=err";</script>';
                }
            }
           
        }

        if($status == true){
            echo "<div id='hide' class=\"alert alert-success \">";
                    echo "<p>Datat u importuan me sukses!</p>";
                    echo "</div>";
                    echo "<script> setTimeout(function(){
                        window.location.href = 'importStundenplan.php';
                        }, 4000);</script>";
        }else{
            echo "<div id='hide' class=\"alert alert-danger \">";
                    echo "<p>Ndodhi nje gabim ju lutem provoni perseri!</p>";
                    echo "</div>";
        }


    }
?>


<div class="container">
        <h1 class="mt-4">Import Stundenplan</h1>
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group col-sm-3">
                <label>File</label>
                <input type="file" name="import" class="form-control" required>
            </div>
            <div class="form-group col-sm-3">
                <a href="delStundenplan.php">Reset All Data</a>
            </div>
            <br>
            <div class="form-group col-sm-3">
                <button type="submit" class="btn btn-dark btn-lg" value="Submit">Import</button>
            </div>
        </form>
    </div>    


<?php
    require_once "footer.php";
?>