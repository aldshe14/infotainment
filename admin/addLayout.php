<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name']) && isset($_POST['beschreibung']) && isset($_POST['file']) && isset($_FILES['image']) ){
 
        // Validate name
            // Prepare an insert statement
            $sql = "Insert into tb_infotainment_layout(name,beschreibung,file,icon) VALUES (:name,:beschreibung,:file,:icon)";

            $tmpName = $_FILES['image']['tmp_name'];       // name of the temporary stored file name
            $fileSize = $_FILES['image']['size'];   // size of the uploaded file
            $fileType = $_FILES['image']['type'];
            $fp = fopen($tmpName, 'r');  // open a file handle of the temporary file
            $imgContent  = fread($fp, filesize($tmpName)); // read the temp file

            fclose($fp); // close the file handle
             
            if($sth = $con->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $sth->bindParam(':name', $_POST["name"]);
                $sth->bindParam(':beschreibung', $_POST["beschreibung"]);
                $sth->bindParam(':file', $_POST["file"]);
                $sth->bindParam(':icon', $imgContent);
                try {
                    $sth->execute();
                    //header('Location: users.php?insert=done');
                    echo "<div id='hide' class=\"alert alert-success \">";
                    echo "<p>Layout u shtua me sukses!</p>";
                    echo "</div>";
                    echo "<script> setTimeout(function(){
                        window.location.href = 'layouts.php';
                     }, 4000);</script>";

                } catch (PDOException $e) {
                    echo "<div id='hide' class=\"alert alert-danger \">";
                    echo "<p>Ndodhi nje gabim ju lutem provoni perseri!</p>";
                    echo "</div>";
                    //header('Location: users.php?insert=err');
                    //echo '<script>window.location.href = "users.php?insert=err";</script>';
                }
            }
        
    }

?>

<div class="container">
        <h1 class="mt-4">Shto Layout</h1>
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group col-sm-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group col-sm-3">
            <label>Beschreibung</label>
                <input type="text" name="beschreibung" class="form-control" required>
            </div>
            <div class="form-group col-sm-3">
                <label>File</label>
                <input type="text" name="file" class="form-control" required>
            </div>
            <div class="form-group col-sm-3">
                <label>Icon</label>
                <input type="file" name="image" class="form-control" required>
            </div>
            <br>
            <div class="form-group col-sm-3">
                <button type="submit" class="btn btn-dark btn-lg" value="Submit">Shto</button>
            </div>
        </form>
    </div>

<?php
    require_once "footer.php";
?>