<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name']) && isset($_POST['beschreibung']) && isset($_POST['file']) ){
 
        // Validate name
            // Prepare an insert statement
            $sql = "UPDATE  tb_infotainment_layout SET name=:name, beschreibung=:beschreibung, file=:file where l_id=:id";
            
            if($sth = $con->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $sth->bindParam(':name', $_POST["name"]);
                $sth->bindParam(':beschreibung', $_POST["beschreibung"]);
                $sth->bindParam(':file', $_POST["file"]);
                $sth->bindParam(':id', $_GET["id"]);
                try {
                    $sth->execute();
                    //header('Location: users.php?insert=done');
                    echo "<div id='hide' class=\"alert alert-success \">";
                    echo "<p>Layout u ndryshua me sukses!</p>";
                    echo "</div>";
                    echo "<script> setTimeout(function(){
                        window.location.href = 'layouts.php';
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
   
    $sql = "SELECT * FROM tb_infotainment_layout WHERE l_id =" . $_GET["id"].";";
    $sth = $con->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container">
    <h1 class="mt-4">Edit Layout</h1>
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?id='.$_GET['id']; ?>" method="post">
        <div class="form-row">
            <div class="form-group col-sm-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo $result[0]['name']?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-sm-3">
                <label>Beschreibung</label>
                <input type="text" name="beschreibung" class="form-control" value="<?php echo $result[0]['beschreibung']?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-sm-3">
                <label>File</label>
                <input type="text" name="file" class="form-control" value="<?php echo $result[0]['file']?>" required>
            </div>
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