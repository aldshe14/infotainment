<?php 

  require_once('php/supplierplan.php'); 

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
        url: "php/weather1.php",
        success: function(result) {
          $(".body").html(result);
        },
        jsonpCallback: 'callbackFnc',
        failure: function() {}
      });
    }, 10000);
  });
      
</script>