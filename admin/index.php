<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if(!isset($_GET['did'])){
        $sql = "SELECT *
        FROM tb_infotainment_display
        where name not like '-';";
        $pdo = $con->prepare($sql);
        $pdo->execute();
        $display = $pdo->fetchAll();
        $did = $display[0]['d_id'];
      }else{
        $did = $_GET['did'];
      }

?>
<div class="container">
    <div class="d-flex justify-content-center row">
    <div class="form-group col-md-3 mt-4">
    <?php
        $sql = "SELECT *
        FROM tb_infotainment_display
        where name not like '---';";
        $pdo = $con->prepare($sql);
        $pdo->execute();
        $display = $pdo->fetchAll(PDO::FETCH_ASSOC);
        echo '<select name="display" class="form-control">';
        foreach($display as $client){
            ?>
            <option value=<?php echo '"'.$client['d_id'].'"'; if($did==$client['d_id']){ echo 'selected>'.$client['name'];}else{echo '>'.$client['name'];}?></option>
        <?php
        }
        echo '</select>';

        $sql = "SELECT *
        FROM tb_infotainment_display_status ds
        JOIN tb_infotainment_display d
        ON d.d_id = ds.d_id
        where d.d_id=:id
        limit 1;";
        $pdo = $con->prepare($sql);
        $pdo->bindParam(':id',$did);
        $pdo->execute();
        $status = $pdo->fetchAll(PDO::FETCH_ASSOC);
    ?>
    </div>
    <div class="form-group col-md-2">
    </div>
    <div class="form-group col-md-3 mt-3">
    <button type="button" class="btn btn-dark btn-lg"><a href="chooseLayout.php?did=<?php echo $did;?>">Layout hinzufügen</a></button>
    </div>
    </div>
    <br>
    <div class="row justify-content-md-center">
    <div class="col col-lg-3">
    <h5>Status  
    <?php
        if($status[0]['status'])
            echo '  <span class="online"></span></h5> Last checked: '.$status[0]['lastChecked'];
        else
        echo '  <span class="offline"></span></h5> Last checked: '.$status[0]['lastChecked'];
        echo '</div>
        <div class="col col-lg-3">
          <h5>IP Address: '.$status[0]['ip'].'</h5>
        </div>';
        echo '
        <div class="col col-lg-3">
          <h5>MAC: '.$status[0]['mac'].'</h5>
        </div>';
    ?>
  </div>
  </div>

</div>


<script type="text/javascript">
    (function () {
        var sel;

        $("select[name=display]").focus(function () {
            // Store the current value on focus, before it changes
            sel = this.value;
        }).change(function() {
            // Do soomething with the previous value after the change
            sel = this.value;
            window.location.href = '?did='+sel;

        });
    })();
    
</script>

<?php
    require_once "timetableLayout.php";
	require_once "footer.php";
?>