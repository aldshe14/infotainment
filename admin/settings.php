<?php
	require_once "connection.php";
    require_once "header.php";
    require_once "navigation.php";
    
    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['email']) && !isset($_SESSION['role']) && !isset($_SESSION['user_id'])){
        header('Location: signin.php');
    }
    
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['apikey']) && isset($_POST['cityid']) && isset($_POST['url']) && isset($_POST['token'])){
 
        $sql = "UPDATE `tb_infotainment_apisettings` SET `weather_apikey`=:apikey,`weather_cityid`=:cityid,
        `website_url`=:url,`chatbot_token`=:token WHERE 1";
            
        if($sth = $con->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $sth->bindParam(':apikey', $_POST["apikey"]);
            $sth->bindParam(':cityid', $_POST["cityid"]);
            $sth->bindParam(':url', $_POST["url"]);
            $sth->bindParam(':token', $_POST["token"]);
            try {
                $sth->execute();
                //header('Location: users.php?insert=done');
                echo "<div id='hide' class=\"alert alert-success \">";
                echo "<p>Settings u ndryshuan me sukses!</p>";
                echo "</div>";

            } catch (PDOException $e) {
                echo "<div id='hide' class=\"alert alert-danger \">";
                echo "<p>Ndodhi nje gabim ju lutem provoni perseri!</p>";
                echo "</div>";
                //header('Location: users.php?insert=err');
                //echo '<script>window.location.href = "users.php?insert=err";</script>';
            }
        }
        
    }
   
    $sql = "SELECT * FROM tb_infotainment_apisettings limit 1";
    $sth = $con->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

?>

	<div class="container">
    <h1 class="mt-4">Settings</h1>
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h3>OpenWeatherMap</h3>
        <div class="form-row">
            <div class="form-group col-md-4">
            <label>API KEY</label>
            <input type="text" class="form-control" name="apikey" value="<?php echo $result[0]['weather_apikey']?>" required>
            </div>
            <div class="form-group col-md-2">
            <label>City ID</label>
            <input type="text" class="form-control" name="cityid" value="<?php echo $result[0]['weather_cityid']?>" required>
            </div>
        </div><br>
        <h3>Website</h3>
        <div class="form-row">
            <div class="form-group col-md-6">
            <label>URL (example http://test.com)</label>
            <input type="text" class="form-control" name="url" value="<?php echo $result[0]['website_url']?>" required>
            </div>
        </div>
        <br><h3>Chatbot</h3>
        <div class="form-row">
            <div class="form-group col-md-6">
            <label>Token</label>
            <input type="text" class="form-control" name="token" value="<?php echo $result[0]['chatbot_token']?>" required>
            </div>
        </div>
        
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-dark btn-lg" value="Submit">Save</button>
            </div>
        </form>
	</div>

<?php
	require_once "footer.php";
?>