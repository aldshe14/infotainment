<?php 
  require_once('php/weather1.php'); 
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
    }, 10000);
  });
      
</script>