<?php
	// Erster Schritt -> Array mit Tabellenname

	$tables=['chatbotMultiLanguage','tb_infotainment_apisettings' ,'tb_infotainment_chatbot_users','tb_infotainment_display','tb_infotainment_fehlendelehrer','tb_infotainment_images','tb_infotainment_kalenderinfo','tb_infotainment_klasse','tb_infotainment_language','tb_infotainment_layout', 'tb_infotainment_layout_sections', 'tb_infotainment_location', 'tb_infotainment_password_reset', 'tb_infotainment_roles', 'tb_infotainment_supplieren', 'tb_infotainment_timetable', 'tb_infotainment_timetable_layout', 'tb_infotainment_unterricht', 'tb_infotainment_users', 'tb_infotainment_weather', 'tb_infotainment_weather_info', 'tb_infotainment_weather_posts'];

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
    //print_r($result);
    //4. Schritt -> Dem Array durchgehen
     foreach($result as $row){
     	// 5. Schritt -> Connection mit jedem Display
     	$ip=$row['ip'];
     	$user="infotainment";
     	$pwd="1nf0tainment";
     	$dbname="infotainment_system";

     	$msg;
     	$Connection;

     	try{
			$Connection= new PDO("mysql:host=$ip;dbname=$dbname;charset=utf8",$user,$pwd);
    		$Connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    		for($i=0; $i < sizeof($tables); $i++){
				// 7. Schritt -> Select die aktuelle Tabelle from server
				echo $tables[$i];
				$statement="Select * from ".$tables[$i].";";
				$pdo = $con->prepare($statement);
				//$pdo->bindParam(1,$tables[$i],PDO::PARAM_STR);
				//$pdo->execute([$tables[$i]]);
				$pdo->execute();
				$arr = $pdo->fetchAll(PDO::FETCH_ASSOC);

				//8. Schritt -> Select die aktuelle Tabelle from display
				$st="Select * from ".$tables[$i].";";
				$pdo = $Connection->prepare($st);
				$pdo->execute();
				$arrr = $pdo->fetchAll(PDO::FETCH_ASSOC);
				//print_r array_diff($arr,$arrr);
				 $diff=array_diff($arr, $arrr);
				//echo array_diff($arr, $arrr);
				 print_r($arrr);
				 print_r($arr);

				if (is_null($diff)){
					echo "Nothing has changed";
				}
				
				else {
					$st="SHOW COLUMNS FROM ".$tables[$i].";";
					$pdo = $Connection->prepare($st);
					$pdo->execute();
					$res = $pdo->fetchAll(PDO::FETCH_ASSOC);
					print_r($res);
					$st="INSERT into ".$tables[$i];
					$st .="(";

					for($j=0; $j<sizeof($res); $j++){
						if($j==sizeof($res)-1){
							$st .= $res[$j]['Field'];
							$st .= ")";
						}
						else{
							$st .= $res[$j]['Field'];
							$st .= ",";

							}
					}
					$st .= "VALUES";
				    $st .= "(";
					for($a=0; $a<sizeof($res); $a++){
						if($a==sizeof($res)-1){
							$st .="?";
							$st .= ")";
						}
						else{
							
							$st .= "?";
							$st .= ",";

						}

					}
					for($b=0; $b<sizeof($res); $b++){
						if (is_null($diff)){
						echo "Nothing has changed i swear";
						}
						else{
							$dicka=array_diff($arrr, $arr);
							// $difference = array_merge(array_diff($arrr, $arr), array_diff($arr, $arrr));
							print_r($dicka);
							foreach ($dicka as $valuea){
								$pdo = $con->prepare($st);
								//$pattern="=>[]";
								//if(preg_match($pattern, $valuea)){
								//	$pdo->bindParam($valuea);
								//	$pdo->execute();
								//	echo "Die Aenderungen wurden gespeichert";
								//}
								//else{
								//	echo "Es hat nicht funktioniert";
								//}
								$pdo->bindParam($valuea);
								$pdo->execute();
								echo "Die Aenderungen wurden gespeichert";

							}
							

						}
					}

					echo $st;

					
	
					}

					//$pdo = $Connection->prepare($st);
					//$pdo->execute();
					//$res = $pdo->fetchAll(PDO::FETCH_ASSOC);
									

					




		}
		}catch(PDOException $e){
    		$msg = "Connection failed:". $e->getMessage();
		}
		// 6. Schritt fuer die Array Tables oben
		

	}

?>