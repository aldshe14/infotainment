<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";


    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title']) && isset($_POST['beschreibung']) && isset($_POST['datum']) ){
 
        // Validate name
            // Prepare an insert statement
            $sql = "UPDATE  tb_infotainment_kalendarinfo SET title=:title, beschreibung=:beschreibung, datum=:datum where k_id=:id";
             
            if($sth = $con->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $sth->bindParam(':title', $_POST["title"]);
                $sth->bindParam(':beschreibung', $_POST["beschreibung"]);
                $sth->bindParam(':datum', $_POST["datum"]);
                $sth->bindParam(':id', $_GET["id"]);
                try {
                    $sth->execute();
                    //header('Location: users.php?insert=done');
                    echo "<div id='hide' class=\"alert alert-success \">";
                    echo "<p>Display u ndryshua me sukses!</p>";
                    echo "</div>";
                    echo "<script> setTimeout(function(){
                        window.location.href = 'kalendarinfo.php';
                     }, 2000);</script>";

                } catch (PDOException $e) {
                    echo "<div id='hide' class=\"alert alert-danger \">";
                    echo "<p>Ndodhi nje gabim ju lutem provoni perseri!</p>";
                    echo "</div>";
                    //header('Location: users.php?insert=err');
                    //echo '<script>window.location.href = "users.php?insert=err";</script>';
                }
            }
        
    }
   
    $sql = "SELECT * FROM tb_infotainment_kalendarinfo WHERE k_id =" . $_GET["id"].";";
    $sth = $con->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container">
    <h1 class="mt-4">Edit Display</h1>
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?id='.$_GET['id']; ?>" method="post">

            <div class="form-group col-sm-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo $result[0]['title']?>" required>
            </div>
            <div class="form-group col-sm-3">
                <label>Beschreibung</label>
                <textarea type="text" name="beschreibung" class="form-control" required><?php echo $result[0]['beschreibung']?></textarea>
            </div>
            <div class="form-group col-sm-3">
                <label>Datum</label>
                <input type="date" name="datum" class="form-control" value="<?php echo $result[0]['datum']?>" required>
            </div>
            <br>
        
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-dark btn-lg" value="Submit">Edit</button>
            </div>
        </form>
	</div>

<?php
	require_once "footer.php";
?>