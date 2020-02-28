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
    $arp = `hostname -I`;
    $lines = explode("\n", $arp);
    //$mac = explode("\t", $lines[1]);
    $ip = $lines[0];
    return $ip;
}

function getTimetable($section,$displayid,$MAC){
    $sql = "SELECT *
    FROM tb_infotainment_timetable t
    JOIN tb_infotainment_layout_sections ls
    ON t.lsection_id = ls.l_id
    JOIN tb_infotainment_timetable_layout tl
    ON t.tl_id = tl.t_id
    JOIN tb_infotainment_display d
    ON tl.display_id = d.d_id
    where d.mac like :mac and ls.name like :section and tl.display_id = :display and (tl.dayofweek & :day > 0);
    ";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(":mac",$MAC);
    $stmt->bindParam(":section",$section);
    $stmt->bindParam(":display",$display);
    $stmt->bindParam(":day",getDay());
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    print_r($result);
}

function getDay(){
    $rawDate = date("Y-m-d");
    $day = date('N', strtotime($rawDate));
    return $day;
}
?>