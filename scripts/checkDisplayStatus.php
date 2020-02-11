<?php

function ping($ip){
    
    $ping = `ping 10.2.7.1 -c 2`;
    echo  exec(`ping -c 1 10.2.7.100`);; 
    $lines = explode(",", $ping);
    //$mac = explode("\t", $lines[1]);
    $res = explode("%", $lines[2]);
    if($res[0]!="100"){
        echo "in";
        return 1;
    }else{
        echo "out";
        return -1;
    }
}
    require_once "connection.php";

    $sql = "SELECT * 
    FROM  tb_infotainment_display order by ip asc;";
    $pdo = $con->prepare($sql);
    
    try {
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);
        //echo $result['city_id'];
        foreach ($result as $display){
            $status = -1;
            $status = ping($display['ip']);
            
            $query = "UPDATE  tb_infotainment_display_status SET status=:status,lastChecked=now() where d_id = :id;";
            $stmt = $con->prepare($query);
            $stmt->bindValue(':status',$status);
            $stmt->bindValue(':id',$display['d_id']);
            try{
                $stmt->execute();
            }catch (PDOException $e) {
                echo $e->getMessage();
            }
            
            
        }
    }catch (PDOException $e) {
    	echo $e->getMessage();
    }
?>