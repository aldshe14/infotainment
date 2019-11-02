<div class="card" style="width: 100%;">
            <?php
                $apiKey = "8d995424b7b7139fc7790d5bf33bde03";
                $cityId = "3184081";
                $googleApiUrl = "http://api.openweathermap.org/data/2.5/weather?id=" . $cityId . "&units=metric&APPID=" . $apiKey;
                
                //echo $googleApiUrl;
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
            ?>

            <h2><?php echo $data->name; ?> Weather Status</h2>
            <div id="time">
                <div>
                <div><?php echo date("l g:i a", $currentTime); ?></div>
                <div><?php echo date("jS F, Y",$currentTime); ?></div>
                <div><?php echo ucwords($data->weather[0]->description); ?></div>
                </div>
            </div>
            <div class="weather-forecast" id="weather">
                <img
                    src="http://openweathermap.org/img/w/<?php echo $data->weather[0]->icon; ?>.png"
                    class="weather-icon" /> <?php echo $data->main->temp; ?>°C <span
                    class="min-temperature"></span>
            </div>
            <div>
                <div>Humidity: <?php echo $data->main->humidity; ?> %</div>
                <div>Wind: <?php echo $data->wind->speed; ?> km/h</div>
            </div>
        </div>
