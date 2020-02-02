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
        failure: function() {},
        complete: function(data) {
          $("").html("Success : ");
          if (data.readyState == '4' && data.status == '200') {

            //document.write("Success : ");
            //document.write(data);
          } else {
            document.writeln("Failed");
          }
        }
      });
    }, 4000);
  });
      
</script>