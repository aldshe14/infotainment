<?php
    require_once('connection.php');
    

    $sql = "SELECT * 
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
        echo '<img style="max-height:60%;width:auto;" src="data:image/jpeg;base64,'.base64_encode($result[0]['icon']) .'" />';
        echo '<br><br><div class="form-group">
        <button type="submit" class="btn btn-dark btn-lg float-right" value="Submit">Shto</button>
    </div>';
        
    }catch (PDOException $e) {
        echo $e->getMessage();
    }
        

    echo '</div></div>';

?>