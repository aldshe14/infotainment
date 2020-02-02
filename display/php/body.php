<?php 

  require_once('php/supplierplan.php'); 

?>

<script>

  
  function fetchbody(){
      $.ajax({
          url: 'weather1.php',
          type: 'post',
          success: function(response){
          // Perform operation on the return value
            $(".body").html(response);
          }
      });
  }
          
  $(document).ready(fetchbody(), 10000);
  });
      
</script>