<?php require_once('php/weather1.php'); ?>

<script>
        
  $(document).ready(function() {
    // Instead of button click, change this.
    setTimeout(function() {
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
    }, 60000);
  });
      
</script>