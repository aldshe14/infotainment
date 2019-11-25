<?php
	require_once "connection.php";
    
    $apiKey = "";
    $cityId = "";

    $sql = "SELECT * 
    FROM  tb_infotainment_weather_info limit 1 ;";
    $pdo = $con->prepare($sql);
    
    try {
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);
        //echo $result['city_id'];
        foreach ($result as $row){
            $apiKey = $row['appid'];
            $cityId = $row['city_id'];
        }
    }catch (PDOException $e) {
    	header("Location: getWeatherData.php");
    }

    $googleApiUrl = "http://api.openweathermap.org/data/2.5/weather?id=" . $cityId . "&units=metric&APPID=" . $apiKey;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    curl_close($ch);
    $data = json_decode($response);
    $currentTime = time();

    $sql = "INSERT INTO `tb_infotainment_weather` (`datum`, `beschreibung`, `temp`, `temp_min`, `temp_max`, `humidity`, `sunrise`, `sunset`, `wind_speed`, `icon`, `city_name`) 
    VALUES(:datum, :beschreibung, :temp, :min, :max, :humidity, :sunrise, :sunset, :windspeed, :icon, :city_name)";
    $sth = $con->prepare($sql);
    $sth->bindValue(':datum', date("Y-m-d H:m:s", $currentTime));
    $sth->bindValue(':beschreibung', ucwords($data->weather[0]->description));
    $sth->bindValue(':temp', $data->main->temp);
    $sth->bindValue(':min', $data->main->temp_min);
    $sth->bindValue(':max', $data->main->temp_max);
    $sth->bindValue(':humidity', $data->main->humidity);
    $sth->bindValue(':sunrise', date('Y-m-d H:i:s',strtotime('+1 hour',strtotime(gmdate("Y-m-d\TH:i:s\Z", $data->sys->sunrise)))));
    $sth->bindValue(':sunset', date('Y-m-d H:i:s',strtotime('+1 hour',strtotime(gmdate("Y-m-d\TH:i:s\Z", $data->sys->sunset)))));
    $sth->bindValue(':windspeed', $data->wind->speed);
    $sth->bindValue(':icon', $data->weather[0]->icon);
    $sth->bindValue(':city_name', $data->name);

    try {
        $sth->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

?>
