<?php
    include "telnet.php";
    $con = new Telnet();
    $time = $_GET['time'];
    $time = date('Y/m/d-H:i:s', strtotime($time));
    $nomes = [];
    $end = [];
    foreach($_GET['address'] AS $check) {
        array_push($nomes, explode('.', end(explode('/', $check)))[0]);
        array_push($end, $_GET['address']);
    }
    $name = strtolower(str_replace(' ', '_', $_GET['name']));
    $con->sendCommand("new $nome broadcast enabled\r\n");
    $con->sendCommand("setup $nome option --fullscreen\r\n");
    for($i = 0; $i < sizeof($nomes); $i++) {
        $con->sendCommand("setup $nomes[$i] input ".$end[$i]."\r\n");
    }
    $con->sendCommand("setup $nome output #transcode{vcodec=mp4v,vb=4096,sfilter=logo:marq,soverlay}:display\r\n");
    $con->sendCommand("new ".$name." schedule enabled\r\n");
    $con->sendCommand("setup ".$name." date ".$time."\r\n");
    $con->sendCommand("setup ".$name." append control $nome play\r\n");
    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>