<?php
error_reporting(E_ALL);
ini_set('display_errors', 'true');
include "rc.php";
$con = new RC();
echo $con->connect();
//header('Location: ' . $_SERVER['HTTP_REFERER']);
?>