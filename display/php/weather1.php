<?php

    $sql = "SELECT * 
    FROM  tb_infotainment_weather
    order by datum desc limit 1 ;";
    $pdo = $con->prepare($sql);

    try {
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row){?>
            <div class="report-container">
                <h2><?php echo $row['city_name']; ?> Weather Status</h2>
                <div class="time">
                    <div><?php echo date("l g:i a", strtotime($row['datum'])); ?></div>
                    <div><?php echo date("jS F, Y",strtotime($row['datum'])); ?></div>
                    <div><?php echo ucwords($row['beschreibung']); ?></div>
                </div>
                <div class="weather-forecast">
                    <img
                        src="http://openweathermap.org/img/w/<?php echo $row['icon']; ?>.png"
                        class="weather-icon" /> <?php echo $row['temp_max']; ?>°C <span
                        class="min-temperature"><?php echo $row['temp_min']; ?>°C</span>
                </div>
                <div class="time">
                    <div>Humidity: <?php echo $row['humidity']; ?> %</div>
                    <div>Wind: <?php echo $row['wind_speed']; ?> km/h</div>
                </div>
            </div>

            <?php
        }
    }catch (PDOException $e) {
        echo "Error ".$e->getMessage();
    }
   

?>

