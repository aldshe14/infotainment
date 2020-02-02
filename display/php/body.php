<?php 

  require_once('php/supplierplan.php'); 

  $sql = "call sp_getLayout(:mac);";
  $stmt = $con->prepare($sql);
  $stmt->bindParam(":mac",$MAC);
  $stmt->execute();
  $result = $stmt->fetch();

?>

<script>
        
  $(document).ready(function() {
    // Instead of button click, change this.
    setInterval(function() {
      jQuery.support.cors = true;
      $.ajax({
        crossDomain: true,
        async: true,
        type: "POST",
        url: "php/supplierplan.php",
        success: function(result) {
          $(".body").html(result);
        },
        jsonpCallback: 'callbackFnc',
        failure: function() {}
      });
    }, 4000);
  });
      
</script>