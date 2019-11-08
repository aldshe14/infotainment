<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }
    
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['anz']) && isset($_POST['date'])){
        $anz = 1; 
        while($anz <= $_POST['anz']){

            if(isset($_POST['stunde'.$anz]) && isset($_POST['lehrer'.$anz]) && isset($_POST['fach'.$anz]) & isset($_POST['check'.$anz])
        && isset($_POST['klasse'.$anz]) && isset($_POST['suplehrer'.$anz]) && isset($_POST['beschreibung'.$anz]) && isset($_POST['raum'.$anz]))
            {
        // Validate name
            // Prepare an insert statement
            $sql = "INSERT INTO `tb_infotainment_supplieren`(`stunde`,`lehrer`,`supplierer`,`klasse`,`raum`,`datum`,`beschreibung`) 
            VALUES(:stunde,:lehrer,:supplierer,:klasse,:raum,:datum,:beschreibung) ";
             
            if($sth = $con->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $sth->bindParam(':stunde', $_POST["stunde".$anz]);
                $sth->bindParam(':lehrer', $_POST["lehrer".$anz]);
                $sth->bindParam(':supplierer', $_POST["suplehrer".$anz]);
                $sth->bindParam(':klasse', $_POST["klasse".$anz]);
                $sth->bindParam(':raum', $_POST["raum".$anz]);
                $sth->bindParam(':datum', $_POST["date"]);
                $sth->bindParam(':beschreibung', $_POST["beschreibung".$anz]);

                try {
                    $sth->execute();
                    //header('Location: users.php?insert=done');
                    if($anz==$_POST['anz']){
                        echo "<div id='hide' class=\"alert alert-success \">";
                        echo "<p>Dekreti u ndryshua me sukses!</p>";
                        echo "</div>";
                    }

                } catch (PDOException $e) {
                    echo "<div id='hide' class=\"alert alert-danger \">";
                    echo "<p>Ndodhi nje gabim ju lutem provoni perseri!</p>";
                    echo "</div>";
                    //header('Location: users.php?insert=err');
                    //echo '<script>window.location.href = "users.php?insert=err";</script>';
                }
            }
            }
            $anz++;
        }
        
    }

    $rawDate = date("Y-m-d");

    $sql = "SELECT * FROM tb_infotainment_unterricht WHERE u_id =" . $_GET["id"].";";
    $sth = $con->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    
    $sql = "SELECT u.u_id as id, u.unterricht_nr as unterricht_nr, u.klasse as klasse, u.lehrer as lehrer, u.raum as raum, u.fach as fach, u.tag as tag, u.stunde as stunde
            FROM tb_infotainment_unterricht u 
            left join tb_infotainment_supplieren s 
            on u.lehrer = s.lehrer and u.stunde = s.stunde and u.tag <> (dayofweek(s.datum)-1)
            WHERE s.lehrer is null and u.tag =" . $result[0]['tag']." and u.lehrer = '".$result[0]['lehrer']."' and u.fach <> 'SU' order by u.stunde asc;";
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
        <div class="form-group col-xs-2">
                <label></label>
            </div>
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
        if($result1){
        foreach($result1 as $row){
            $anz = $anz+1;
            echo '<div class="form-row">
                <div class="form-check">
                <input type="checkbox" class="form-check-input" name="check'.$anz.'" value="">
                </div>
                <div class="form-group col-md-1">
                <input type="number" class="form-control" name="stunde'.$anz.'" value="'.$row["stunde"].'" readonly>
                </div>
                <div class="form-group col-md-1">           
                <input type="text" class="form-control" name="lehrer'.$anz.'" value="'.$row["lehrer"].'" readonly>
                </div>
                <div class="form-group col-md-1">
                <input type="text" class="form-control" name="fach'.$anz.'" value="'.$row["fach"].'" readonly>
                </div>
                <div class="form-group col-md-1">
                <input type="text" class="form-control" name="klasse'.$anz.'" value="'.$row["klasse"].'" readonly>
                </div>
                <div class="form-group col-md-1">
                <input type="text" class="form-control" name="raum'.$anz.'" value="'.$row["raum"].'">
                </div>
                <div class="form-group col-md-1">';
                $sql = "SELECT u.lehrer as lehrer 
                FROM tb_infotainment_unterricht u 
                left join tb_infotainment_supplieren s
                on u.lehrer = s.supplierer
                WHERE u.tag =" . $row['tag']." and u.stunde = ".$row['stunde']." and u.fach = 'SU' and s.supplierer is null;";
                $sth = $con->prepare($sql);
                $sth->execute();
                $supplierer = $sth->fetchAll(PDO::FETCH_ASSOC);

                $sql1 = "SELECT lehrer
                FROM tb_infotainment_unterricht
                WHERE ((tag =" . $row['tag']." and stunde = ".$row['stunde'].") or (tag <>" . $row['tag'].")) and lehrer <>''
                group by lehrer";
                $stmt = $con->prepare($sql1);
                $stmt->execute();
                $supplierer1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo '
                    <select name="suplehrer'.$anz.'" class="form-control">';
                    if($supplierer)    
                    echo '<option value="---">----</option>';
                    foreach($supplierer as $row){
                        echo '<option value="'.$row['lehrer'].'">'.$row['lehrer'].' - Bereit</option>
                    ';}
                    
                    echo '
                    <option value="---">----</option>';
                    foreach($supplierer1 as $row){
                        echo '<option value="'.$row['lehrer'].'">'.$row['lehrer'].'</option>
                    ';}
                    echo '
                    </select>
                </div>
                <div class="form-group col-md-2">
                <input type="text" class="form-control" name="beschreibung'.$anz.'" value="">
                </div>
            </div>
        '; }}
        ?> 
            <input type="text" name="anz" value="<?php echo $anz; ?>" hidden>
            <input type="text" name="date" value="<?php echo date('Y-m-d', strtotime($rawDate . ' +'.$_GET['d'].' day')); ?>" hidden>
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg" value="Submit">Ndrysho</button>
            </div>
        </form>
    </div>    


<?php
    require_once "footer.php";
?>