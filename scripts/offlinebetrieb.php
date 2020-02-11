<?php
	// Erster Schritt -> Array mit Tabellenname

	$tables=['chatbotMultiLanguage','tb_infotainment_apisettings' ,'tb_infotainment_chatbot_users','tb_infotainment_display','tb_infotainment_fehlendelehrer','tb_infotainment_klasse','tb_infotainment_language','tb_infotainment_layout', 'tb_infotainment_layout_sections', 'tb_infotainment_location', 'tb_infotainment_password_reset', 'tb_infotainment_roles', 'tb_infotainment_supplieren', 'tb_infotainment_timetable', 'tb_infotainment_timetable_layout', 'tb_infotainment_unterricht', 'tb_infotainment_users', 'tb_infotainment_weather', 'tb_infotainment_weather_info', 'tb_infotainment_weather_posts'];

	$tables1=['chatbotMultiLanguage'];
	// 2. Schritt -> Connection mit Server(local)


	$servername="185.62.175.221:3306";
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

	$sql = "SELECT * from tb_infotainment_display d join tb_infotainment_display_status ds on d.d_id = ds.d_id where ds.status=1 ;";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //print_r($result);
    //4. Schritt -> Dem Array durchgehen
     foreach($result as $row){
     	// 5. Schritt -> Connection mit jedem Display
     	$ip=$row['ip'];
     	$user="rrushberry";
     	$pwd="rrushberry";
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
				
				$st="SHOW COLUMNS FROM ".$tables[$i].";";
				$pdo = $con->prepare($st);
				$pdo->execute();
				$res = $pdo->fetchAll(PDO::FETCH_ASSOC);
				//echo "<br>Show Columns<br>";
				//print_r($res);
				try{
					$stmt="CREATE TABLE IF NOT EXISTS ".$tables[$i]."(";
					for($k=0; $k<sizeof($res);$k++) {
						if($k!=sizeof($res)-1){
							$stmt.=' '.$res[$k]['Field'].' '.$res[$k]['Type'].' ';
							if($res[$k]['Null']=="NO"){
								$stmt.='NOT NULL ';
							}
							if($res[$k]['Key']=="PRI"){
								$stmt.='PRIMARY KEY '.$res[$k]['Extra'].' ,';
							}else{
								$stmt.=',';
							}

						}else{
							$stmt.=' '.$res[$k]['Field'].' '.$res[$k]['Type'].' ';
							if($res[$k]['Null']=="NO"){
								$stmt.='NOT NULL ';
							}
							if($res[$k]['Key']=="PRI"){
								$stmt.='PRIMARY KEY '.$res[$k]['Extra'].');';
							}else{
								$stmt.=');';
							}
						}
					}
					//echo $stmt;
					$pdo = $Connection->prepare($stmt);
					$pdo->execute();
					echo "<br>".$stmt."<br>";
				}catch(PDOException $e){
		    		echo "<br> Create failed:". $e->getMessage();
		    		
				}
				
				

				$st="Select * from ".$tables[$i].";";
				$pdo = $Connection->prepare($st);
				try{
					$pdo->execute();
					$arrr = $pdo->fetchAll(PDO::FETCH_ASSOC);

				}catch(PDOException $e){
					$arrr[][]='';
		    		echo "<br>Select failed:". $e->getMessage();
				}
				
				echo "<br>";
				print_r($arrr);
				foreach ($arr as $tbl) {
					if(!empty($arrr)){
						foreach ($arrr as $cmp) {
						$diff = array_diff($tbl, $cmp);
						echo "<br>Diff<br>";
						print_r($diff);
						echo "<br><br>";
						if (empty($diff)){
							echo "Nothing has changed";
						}else {
							
							$st="INSERT into ".$tables[$i];
							$st .="(";

							for($j=0; $j<sizeof($res); $j++){
								if($res[$j]['Key']==""){
									if($j==sizeof($res)-1){
										$st .= $res[$j]['Field'];
										$st .= ")";
									}
									else{
										$st .= $res[$j]['Field'];
										$st .= ",";

									}
								}
								
							}
							$index = 0;
							$st .= "VALUES";
						    $st .= "(";
							for($a=0; $a<sizeof($res); $a++){
								if($res[$a]['Key']==""){
									if($a==sizeof($res)-1){
										$st .="?";
										$st .= ");";
										$index++;
									}
									else{
										$index++;
										$st .= "?";
										$st .= ",";

									}
								}

							}
							echo "<br>Statement<br>".$st."<br>";
							echo "<br>".$index."<br>";
							foreach ($diff as $valuea){
								$pdo = $Connection->prepare($st);
								//$pattern="=>[]";
								//if(preg_match($pattern, $valuea)){
								//	$pdo->bindParam($valuea);
								//	$pdo->execute();
								//	echo "Die Aenderungen wurden gespeichert";
								//}
								//else{
								//	echo "Es hat nicht funktioniert";
								//}
								try{
									$insert = 0;
									while($index>1){
										$pdo->bindParam($insert,$valuea);
										$index--;
										$insert++;
									}
									$pdo->execute();
									
									echo "Die Aenderungen wurden gespeichert";
								}catch(PDOException $e){
						    		echo "Connection failed:". $e->getMessage();
								}
														

							}
						}
					}
					}else{
						$st="INSERT into ".$tables[$i];
							$st .="(";

							for($j=0; $j<sizeof($res); $j++){
								if($res[$j]['Key']==""){

									if($j==sizeof($res)-1){
										$st .= $res[$j]['Field'];
										$st .= ")";
									}
									else{
										$st .= $res[$j]['Field'];
										$st .= ",";

									}
								}
							}
							$index = 0;
							$st .= "VALUES";
						    $st .= "(";
							for($a=0; $a<sizeof($res); $a++){
								if($res[$a]['Key']==""){

									if($a==sizeof($res)-1){
										$st .="?";
										$st .= ");";
										$index++;
									}
									else{
										$index++;
										$st .= "?";
										$st .= ",";

									}
								}

							}
							echo "<br>Statement<br>".$st."<br>";
							echo "<br>".$index."<br>";
							foreach ($tbl as $valuea){
								$pdo = $Connection->prepare($st);
								//$pattern="=>[]";
								//if(preg_match($pattern, $valuea)){
								//	$pdo->bindParam($valuea);
								//	$pdo->execute();
								//	echo "Die Aenderungen wurden gespeichert";
								//}
								//else{
								//	echo "Es hat nicht funktioniert";
								//}
								try{
									$insert = 0;
									while($index>1){
										$pdo->bindParam($insert,$valuea);
										$index--;
										$insert++;
									}
									$pdo->execute();
									
									//echo "Die Aenderungen wurden gespeichert";
								}catch(PDOException $e){
						    		echo "Insert Failed". $e->getMessage();
								}
							}
					}
					
				}
				//$diff=array_diff($arr, $arrr);
				//echo array_diff($arr, $arrr);
				// print_r($arrr);
				// print_r($arr);
		}
		}catch(PDOException $e){
    		$msg = "Connection failed:". $e->getMessage();
		}
		// 6. Schritt fuer die Array Tables oben
		

	}

?>