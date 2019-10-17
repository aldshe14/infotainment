<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }
    
    $sql = "SELECT * FROM tb_infotainment_unterricht;";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($result as $row) {  
        echo $row['u_id']." ".$row['unterricht_nr']." ".$row['klasse']." ".$row['lehrer']."<br>";
    }
   
?>
<div class="container">

</div>

<?php
	require_once "footer.php";
?>