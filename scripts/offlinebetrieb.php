<?php
	// Erster Schritt -> Array mit Tabellenname

	$tables=['chatbotMultiLanguage','tb_infotainment_apisettings','tb_infotainment_chatbot_images','tb_infotainment_chatbot_users','tb_infotainment_display','tb_infotainment_fehlendelehrer','tb_infotainment_images','tb_infotainment_kalenderinfo','tb_infotainment_klasse','tb_infotainment_language','tb_infotainment_layout', 'tb_infotainment_layout_sections', 'tb_infotainment_location', 'tb_infotainment_password_reset', 'tb_infotainment_roles', 'tb_infotainment_supplieren', 'tb_infotainment_timetable', 'tb_infotainment_timetable_layout', 'tb_infotainment_unterricht', 'tb_infotainment_users', 'tb_infotainment_weather', 'tb_infotainment_weather_info', 'tb_infotainment_weather_posts']

	// 2. Schritt -> Connection mit Server(local)


	$servername="185.62.175.221:33066";
	$usr="infotainment";
	$pswd="1nf0tainment";
	$dbname="infotainment_system";

	$message;
	$con;

	try{
		$con= new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8",$usr,$pswd);
    	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $e){
    	$message = "Connection failed:". $e->getMessage();
	}

	// 3.Schritt -> Displays selektieren

	$sql = "SELECT * from tb_infotainment_display;";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //4. Schritt -> Dem Array durchgehen
     foreach($result as $row){
     	// 5. Schritt -> Connection mit jedem Display
     	$ip=$row[2];
     	$user="infotainment";
     	$pwd="1nf0tainment";
     	$dbname="infotainment_system";

     	$msg;
     	$Connection;

     	try{
			$Connection= new PDO("mysql:host=$ip;dbname=$dbname;charset=utf8",$user,$pwd);
    		$Connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
    		$msg = "Connection failed:". $e->getMessage();
		}
		// 6. Schritt fuer die Array Tables oben
		for($i=0; $i < strlen($tables); $i++){
			

				// 7. Schritt -> Select die aktuelle Tabelle from server
				$statement="Select * from ?;"
				$stmt = $con->prepare($sql);
				$statement->execute($tables[i])
				$arr = $statement->fetchAll(PDO::FETCH_ASSOC);

				//8. Schritt -> Select die aktuelle Tabelle from display
				$st="Select * from ?"
				$st = $Connection->prepare($sql);
				$st->execute($tables[i])
				$arrr = $st->fetchAll(PDO::FETCH_ASSOC);

				$res=array_diff($arr, $arrr)	
				if ($arr==$arrr){
					echo "Nothing has changed"
				}
				else {

					$insertstatement= "insert into ?(?,?,?) values(?,?,?;"
					$insertstatement->bind_param($a, $lastname, $email);

				}

					




		}

		}
	}







     }

?>