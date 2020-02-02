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


?>