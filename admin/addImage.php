<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";
    
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image']) && isset($_POST['name'])){
        
        $status = true;

        $tmpName = $_FILES['image']['tmp_name'];       // name of the temporary stored file name
        $fileSize = $_FILES['image']['size'];   // size of the uploaded file
        $fileType = $_FILES['image']['type'];
        $fp = fopen($tmpName, 'r');  // open a file handle of the temporary file
        $imgContent  = fread($fp, filesize($tmpName)); // read the temp file

        fclose($fp); // close the file handle


        $sql = "Insert into tb_infotainment_images(name,image,type) VALUES (:name,:image,:type)";

            
            if($sth = $con->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $sth->bindParam(':name', $_POST['name']);
                $sth->bindParam(':image', $imgContent);
                $sth->bindParam(':type', $fileType);
                try {
                    $sth->execute();
               
                } catch (PDOException $e) {
                    $status = false;
                    echo $e->getMessage();
                    //header('Location: users.php?insert=err');
                    //echo '<script>window.location.href = "users.php?insert=err";</script>';
                }
            }
           
        

        if($status == true){
            echo "<div id='hide' class=\"alert alert-success \">";
                    echo "<p>Foto u shtua me sukses!</p>";
                    echo "</div>";
                    echo "<script> setTimeout(function(){
                        window.location.href = 'images.php';
                        }, 4000);</script>";
        }else{
            echo "<div id='hide' class=\"alert alert-danger \">";
                    echo "<p>Ndodhi nje gabim ju lutem provoni perseri!</p>";
                    echo "</div>";
        }


    }
?>


<div class="container">
    <h1 class="mt-4">Bild Hochladen</h1>
    <br>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group col-sm-4">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group col-sm-3">
            <label>Datei</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <br>
        <div class="form-group col-sm-3">
            <button type="submit" class="btn btn-dark btn-lg" value="Submit">Hochladen</button>
        </div>

    </form>
</div>    


<?php
    require_once "footer.php";
?>