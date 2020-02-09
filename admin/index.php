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
    <div class="d-flex justify-content-center">
    <div class="form-group col-md-3 mt-4">
    <?php
        $sql = "SELECT *
        FROM tb_infotainment_display
        where name not like '-';";
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
    
    $(document).ready(function(){
    $(".test").click(function(){
            $(this).next().show();
            $(this).next().hide();
        });

    });
    
</script>

<?php
    require_once "timetableLayout.php";
	require_once "footer.php";
?>