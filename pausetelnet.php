<?php
error_reporting(E_ALL);
ini_set("display_errors", "true");
include "telnet.php";
$con = new Telnet();
$con->sendCommand("control my_media pause\r\n");
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>