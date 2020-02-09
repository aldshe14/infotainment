<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if(!isset($_GET['id'])){
        die();
    }

    $sql = "call sp_getTimetableLayoutDetails(:id);";
    $pdo = $con->prepare($sql);
    $pdo->bindParam(':id',$_GET['id']);
    $pdo->execute();
    $result = $pdo->fetchAll(PDO::FETCH_ASSOC);


   
?>

<div class="container">
    <div class="row">
        <div class="col-md-4 mt-3">
            <h1><?php echo $result[0]['displayname']." - ".$result[0]['layoutname']; ?></h1>
            <?php echo $result[0]['von']." - ".$result[0]['bis']; ?>
        </div>

    </div>
    <br>
    <div class="flex-layout1">
        <?php
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
            echo '<div class="modal fade" id="'.$part['layoutsection'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">'.$part['layoutsection'].'</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form id="contactForm" name="contact" role="form">
                        <div class="form-group col-sm-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group col-sm-3">
                        <label>Beschreibung</label>
                            <input type="text" name="beschreibung" class="form-control" required>
                        </div>
                        <div class="form-group col-sm-3">
                            <label>File</label>
                            <input type="text" name="file" class="form-control" required>
                        </div>
                        <br>
    
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-success" id="submit">
                </div>
                </div>
            </div>
        </div>';
        }
    ?>  

<script>
    $(document).ready(function(){	
	$("#contactForm").submit(function(event){
		submitForm();
		return false;
	});
    function submitForm(){
            $.ajax({
                type: "POST",
                url: "viewLayout.php",
                cache:false,
                data: $('form#contactForm').serialize(),
                success: function(response){
                    $("#contact").html(response)
                    $("#exampleModalLong").modal('hide');
                },
                error: function(){
                    alert("Error");
                }
            });
        }
});
</script>

<?php
	require_once "footer.php";
?>