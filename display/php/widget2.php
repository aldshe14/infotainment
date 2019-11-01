<?php require_once('php/website.php'); ?>

<script>
        
  $(document).ready(function() {
    // Instead of button click, change this.
    setTimeout(function() {
      jQuery.support.cors = true;
      $.ajax({
        crossDomain: true,
        async: true,
        type: "POST",
        url: "php/website.php",
        success: function(result) {
          $(".widget2").html(result);
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
    }, 2000);
  });

</script>