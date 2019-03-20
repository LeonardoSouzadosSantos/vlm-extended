<?php include "telnet.php";
$con = new Telnet();
$con->sendCommand("@logo logo-file $end\r\n"); ?>