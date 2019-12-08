<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";

    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image']) && isset($_POST['name'])) ){
            $tmpName = $_FILES['image']['tmp_name'];       // name of the temporary stored file name
            $fileSize = $_FILES['image']['size'];   // size of the uploaded file
            $fileType = $_FILES['image']['type'];
            $fp = fopen($tmpName, 'r');  // open a file handle of the temporary file
            $imgContent  = fread($fp, filesize($tmpName)); // read the temp file

            fclose($fp); // close the file handle
 
            // Prepare an insert statement
            $sql = "UPDATE  tb_infotainment_images SET name=:name, image=:image, type=:type where i_id=:id";
             
            if($sth = $con->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $sth->bindParam(':name', $_POST['name']);
                $sth->bindParam(':image', $imgContent);
                $sth->bindParam(':type', $fileType);
                $sth->bindParam(':id', $_GET["id"]);
                try {
                    $sth->execute();
                    echo "<div id='hide' class=\"alert alert-success \">";
                    echo "<p>Foto u ndryshua me sukses!</p>";
                    echo "</div>";
                    echo "<script> setTimeout(function(){
                        window.location.href = 'images.php';
                     }, 2000);</script>";

                } catch (PDOException $e) {
                    echo "<div id='hide' class=\"alert alert-danger \">";
                    echo "<p>Ndodhi nje gabim ju lutem provoni perseri!".$e->getMessage()."</p>";
                    echo "</div>";
                }
            }
        
    }
   
    $sql = "SELECT * FROM tb_infotainment_images WHERE i_id =" . $_GET["id"].";";
    $sth = $con->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container">
    <h1 class="mt-4">Edit Image</h1>
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?id='.$_GET['id']; ?>" method="post">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Name</label>
                <input type="text" class="form-control" name="name" value="<?php echo $result[0]['name']?>" required>
            </div>
            <div class="form-group col-md-4">
                <label>File</label>
                <input type="file" name="image" class="form-control" required>
            </div>
        </div>
        
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-dark btn-lg" value="Submit">Edit</button>
            </div>
        </form>
	</div>

<?php
	require_once "footer.php";
?>