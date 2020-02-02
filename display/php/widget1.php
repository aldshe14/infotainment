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
        url: "php/weather1.php",
        success: function(result) {
          $(".widget1").html(result);
        },
        jsonpCallback: 'callbackFnc',
        failure: function() {}
      });
    }, 60000);
  });
      
</script>