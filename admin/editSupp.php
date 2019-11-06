<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }
    
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['stunde1']) && isset($_POST['lehrer1']) && isset($_POST['fach1'])
    && isset($_POST['klasse1']) && isset($_POST['suplehrer1']) && isset($_POST['beschreibung1']) && isset($_POST['raum1']) && isset($_POST['anz'])){
 
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

    $sql = "SELECT * FROM tb_infotainment_unterricht WHERE tag =" . $result[0]['tag']." and lehrer = '".$result[0]['lehrer']."' and fach <> 'SU' order by stunde asc;";
    $sth = $con->prepare($sql);
    $sth->execute();
    $result1 = $sth->fetchAll(PDO::FETCH_ASSOC);

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
            <div class="form-group col-md-1">
                <label>Stunde</label>
            </div>
            <div class="form-group col-md-1">
                <label>Lehrer</label>
            </div>
            <div class="form-group col-md-1">
                <label>Fach</label>
            </div>
            <div class="form-group col-md-1">
                <label>Klasse</label>
            </div>
            <div class="form-group col-md-1">
                <label>Raum</label>
            </div>
            <div class="form-group col-md-1">
                <label>Supplierer</label>
            </div>
            <div class="form-group col-md-2">
                <label>Beschreibung</label>
            </div>
        </div>
        
        <?php 
        $anz=0;
        foreach($result1 as $row){
            $anz = $anz+1;
            echo '<div class="form-row">
                <div class="form-group col-md-1">
                <input type="number" class="form-control" name="stunde'.$anz.'" value="'.$row["stunde"].'" disabled>
                </div>
                <div class="form-group col-md-1">           
                <input type="text" class="form-control" name="lehrer'.$anz.'" value="'.$row["lehrer"].'" disabled>
                </div>
                <div class="form-group col-md-1">
                <input type="text" class="form-control" name="fach'.$anz.'" value="'.$row["fach"].'" disabled>
                </div>
                <div class="form-group col-md-1">
                <input type="text" class="form-control" name="klasse'.$anz.'" value="'.$row["klasse"].'" disabled>
                </div>
                <div class="form-group col-md-1">
                <input type="text" class="form-control" name="raum'.$anz.'" value="'.$row["raum"].'">
                </div>
                <div class="form-group col-md-1">';
                $sql = "SELECT * FROM tb_infotainment_unterricht WHERE tag =" . $row['tag']." and stunde = ".$row['stunde']." and fach = 'SU';";
                $sth = $con->prepare($sql);
                $sth->execute();
                $supplierer = $sth->fetchAll(PDO::FETCH_ASSOC);
                echo '
                    <select name="suplehrer'.$anz.'" class="form-control">
                        <option value="0">----</option>';
                    foreach($supplierer as $row){
                        echo '<option value="'.$row['lehrer'].'">'.$row['lehrer'].'</option>
                    ';}
                    
                    echo '
                    </select>
                </div>
                <div class="form-group col-md-2">
                <input type="text" class="form-control" name="beschreibung'.$anz.'" value="">
                </div>
            </div>
        '; }
        ?> 
            <input type="text" name="anz" value="<?php echo $anz; ?>" hidden>
            
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg" value="Submit">Ndrysho</button>
            </div>
        </form>
    </div>    


<?php
    require_once "footer.php";
?>