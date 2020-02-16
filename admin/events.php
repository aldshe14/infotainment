<?php
    require_once('connection.php');
    require_once('header.php');
    require_once('navigation.php');

    $sql = "SELECT name
    FROM tb_infotainment_layout
    where file = :layout
    LIMIT 1;";
    $pdo = $con->prepare($sql);
    $pdo->bindParam(':layout',$_GET['layout']);
    try {
        $pdo->execute();
        $result = $pdo->fetchAll();
        echo '<div class="cd-schedule-modal__event-info">
        <div><h3>'.$result[0]['name'].'</h3><br>';
        //echo '<img style="max-height:60%;width:auto;" src="data:image/jpeg;base64,'.base64_encode($result[0]['icon']) .'" />';
        $sql = "call sp_getTimetableLayoutDetails(:id);";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':id',$_GET['id']);
        $stmt->execute();
        $timetable = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $layout = str_replace(" ","",$timetable[0]['layoutname']);
        echo '<div class="flex-'.strtolower($layout).'">';
        foreach($timetable as $part){
            echo '<div class="flex-'.$part['layoutsection'].'" data-toggle="modal" data-target="#'.$part['layoutsection'].'">';
            $sql = "call sp_getTimetableDetails(:id);";
                $pdo = $con->prepare($sql);
                $pdo->bindParam(':id',$part['t_id']);
                $pdo->execute();
                $res = $pdo->fetchAll(PDO::FETCH_ASSOC);
                echo '<table>';
                    foreach($res as $section){
                        if($section['sectionname']==$part['layoutsection']){
                            echo '<tr><td><h5> '.$section['name']." </h5></td><td><h5>".$section['von']." - ".$section['bis']."</h5></td></tr>";
                        }
                    }
            echo '</table></div>';
        }
        echo '</div>';
        echo '<br><br><div class="form-group">
            <a class="btn btn-dark float-right" href="viewLayout.php?id='.$_GET['id'].'" role="button">View Layout</a>
        </div>';
    }catch (PDOException $e) {
        echo $e->getMessage();
    }
        

    echo '</div></div>';

?>