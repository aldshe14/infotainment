<?php 
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name']) && isset($_POST['beschreibung']) && isset($_POST['file'])){
        echo "worked";
    }
    ?>