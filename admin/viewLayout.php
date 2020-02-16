<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";
    require_once "functions.php";

    if(!isset($_GET['id'])){
        die();
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
    }

    $sql = "call sp_getTimetableLayoutDetails(:id);";
    $pdo = $con->prepare($sql);
    $pdo->bindParam(':id',$_GET['id']);
    $pdo->execute();
    $result = $pdo->fetchAll(PDO::FETCH_ASSOC);

    if(!isset($result[0]['displayname'])){
        die();
    }

   
?>

<div class="container">
    <div class="row">
        <div class="col-md-4 mt-3">
            <h1><?php echo $result[0]['displayname']." - ".$result[0]['layoutname']; ?></h1>
            <?php 
            
                echo $result[0]['von']." - ".$result[0]['bis'].' | '; 
                getDayName($result[0]['dayofweek']);
            
            ?>

        </div>

    </div>
    <br>
    
        <?php
            $layout = str_replace(" ","",$result[0]['layoutname']);
            echo '<div class="flex-'.strtolower($layout).'">';
            foreach($result as $part){
                echo '<div class="flex-'.$part['layoutsection'].'" data-toggle="modal" data-target="#'.$part['layoutsection'].'"></a>';
                $sql = "call sp_getTimetableDetails(:id);";
                $pdo = $con->prepare($sql);
                $pdo->bindParam(':id',$part['t_id']);
                $pdo->execute();
                $res = $pdo->fetchAll(PDO::FETCH_ASSOC);
                    foreach($res as $section){
                        if($section['sectionname']==$part['layoutsection']){
                            echo $section['name']." ".$section['von']." ".$section['bis']."<br>";
                        }
                    }
                echo '</div>';
            }
        ?>  
    </div>

    <br>
    <?php
        foreach($result as $part){
            echo '<div class="modal fade" id="'.$part['layoutsection'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header border-bottom-0">
                  <h5 class="modal-title" id="exampleModalLabel">'.ucfirst($part['layoutname']).' - '.ucfirst($part['layoutsection']).'</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="';echo htmlspecialchars($_SERVER["PHP_SELF"]).'?id='.$_GET['id'].'&section='.$part['layoutsection'];
                echo '" method="post">
                  <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">';
                            if($part['layoutsection']=="header")
                                getHeaderOptions();
                            else if($part['layoutsection']=="body")
                                getBodyOptions();
                            else if($part['layoutsection']=="widget1")
                                getWidget1Options();
                            else if($part['layoutsection']=="widget2")
                                getWidget2Options();
                            else
                                getFooterOptions();
                    
                        echo '</div>
                        </div>
                    </div>
                  <div class="modal-footer border-top-0 ">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-dark">Submit</button>
                  </div>
                </form>
              </div>
            </div>
          </div>';
        }
    ?>  
    

<?php
	require_once "footer.php";
?>