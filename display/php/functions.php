<?php 

function getMac(){
    $mac = false;
    $arp = `ip address | grep link/ether`;
    $lines = explode("\n", $arp);
    //$mac = explode("\t", $lines[1]);
    $mac = explode(" ", $lines[0]);
    return $mac[5];
    
}

function getIPAddress(){
    $ip = false;
    $arp = `ip address | grep "inet "`;
    $lines = explode("\n", $arp);
    //$mac = explode("\t", $lines[1]);
    $ip = explode(" ", $lines[1]);
    $ip = explode("/", $ip[5]);
    return $ip[0];
}

getTimetable($section,$displayid,$MAC){
    $sql = "SELECT *
    FROM tb_infotainment_timetable t
    JOIN tb_infotainment_layout_section ls
    ON t.lsection_id = ls.l_id
    JOIN tb_infotainment_timetable_layout tl
    ON t.tl_id = tl.t_id
    where d.mac = :mac and ls.name=:section and tl.display_id = :display ;
    ";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":mac",$MAC);
    $stmt->bindParam(":section",$section);
    $stmt->bindParam(":display",$display);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    print_r($result);
}
?>