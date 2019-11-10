<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }
    $rawDate = date("Y-m-d");
    
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['anz']) && isset($_POST['date'])){
        $anz = 1; 
        while($anz <= $_POST['anz']){

            if(isset($_POST['stunde'.$anz]) && isset($_POST['lehrer'.$anz]) && isset($_POST['fach'.$anz]) & isset($_POST['check'.$anz])
        && isset($_POST['klasse'.$anz]) && isset($_POST['suplehrer'.$anz]) && isset($_POST['beschreibung'.$anz]) && isset($_POST['raum'.$anz]))
            {
        // Validate name
            // Prepare an insert statement
            $sql = "INSERT INTO `tb_infotainment_supplieren`(`supplierer`,`beschreibung`) 
            VALUES(:supplierer,:beschreibung) ";
             
            if($sth = $con->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $sth->bindParam(':supplierer', $_POST["suplehrer".$anz]);
                //$sth->bindParam(':datum', $_POST["date"]);
                $sth->bindParam(':beschreibung', $_POST["beschreibung".$anz]);


                
                try {
                    $sth->execute();

                    $sql = "SELECT Last_Insert_Id() as id;";
                    $stmt = $con->prepare($sql);
                    $stmt->execute();
                    $last = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo $last[0]["id"];
                    $sql = "INSERT into tb_infotainment_supp_unter(u_id,s_id,woche) values(:u_id,:s_id,:woche)";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(':u_id', $_POST["u_id".$anz]);
                    $stmt->bindParam(':s_id', $last[0]["id"]);
                    $stmt->bindValue(':woche', date('Y',strtotime($rawDate)).''.date('W',strtotime($rawDate . ' +'.$_GET['d'].' day')));
                    $stmt->execute();
                    //header('Location: users.php?insert=done');
                    if($anz==$_POST['anz']){
                        echo "<div id='hide' class=\"alert alert-success \">";
                        echo "<p>Dekreti u ndryshua me sukses!</p>";
                        echo "</div>";
                        
                    }
                    header('Location: supplierplan.php?day='.$_GET['day']);

                } catch (PDOException $e) {
                    echo "<div id='hide' class=\"alert alert-danger \">";
                    echo "<p>Ndodhi nje gabim ju lutem provoni perseri!</p>" .$e->getMessage();
                    echo "</div>";
                    //header('Location: users.php?insert=err');
                    //echo '<script>window.location.href = "users.php?insert=err";</script>';
                }
            }
            }
            $anz++;
        }
        
    }

    $sql = "SELECT * FROM tb_infotainment_unterricht WHERE u_id =" . $_GET["id"].";";
    $sth = $con->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    
    $sql = "SELECT *, u.u_id as u_id
            from tb_infotainment_unterricht u
            left join tb_infotainment_supp_unter z
            on u.u_id = z.u_id
            left join tb_infotainment_supplieren s
            on z.s_id = s.s_id
            where u.fach <> 'SU' and u.tag = :tag and supplierer is null and u.lehrer = :lehrer or woche <> :woche
            having u.tag = :tag1
            order by u.stunde asc
            ";
    $sth = $con->prepare($sql);
    $sth->bindParam(":tag",$result[0]['tag']);
    $sth->bindParam(":lehrer",$result[0]['lehrer']);
    $sth->bindValue(":woche",date('Y',strtotime($rawDate)).''.date('W',strtotime($rawDate . ' +'.$_GET['d'].' day')));
    $sth->bindParam(":tag1",$result[0]['tag']);
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?id='.$_GET['id'].'&d='.$_GET['d'].'&day='.$_GET['day']; ?>" method="post">
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
                <input type="text" name="u_id'.$anz.'" value="'.$row['u_id'].'" hidden>
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
                <input type="text" class="form-control" name="raum'.$anz.'" value="'.$row["raum"].'" readonly>
                </div>
                <div class="form-group col-md-1">';
                $sql = "SELECT u.lehrer as lehrer
                        from tb_infotainment_unterricht u
                        left join tb_infotainment_supplieren s
                        on u.lehrer = s.supplierer
                        where u.fach = 'SU' and tag =:tag and stunde=:stunde and s.supplierer is null";
                $sth = $con->prepare($sql);
                $sth->bindParam(":tag",$row['tag']);
                $sth->bindParam(":stunde",$row['stunde']);
                $sth->execute();
                $supplierer = $sth->fetchAll(PDO::FETCH_ASSOC);

                $sql1 = "SELECT u.lehrer as lehrer
                        from tb_infotainment_unterricht u
                        left join (select lehrer
                        from tb_infotainment_unterricht tu
                        where tu.stunde = :stunde and tu.tag=:tag ) u1
                        on u.lehrer = u1.lehrer
                        left join tb_infotainment_supp_unter z
                        on z.u_id = u.u_id
                        where u.tag=:tag1 and z.u_id is null and u.fach <> 'SU' and u.lehrer <> '' and u1.lehrer is null
                        group by u.lehrer;";
                $stmt = $con->prepare($sql1);
                $stmt->bindParam(":tag",$row['tag']);
                $stmt->bindParam(":stunde",$row['stunde']);
                $stmt->bindParam(":tag1",$row['tag']);
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