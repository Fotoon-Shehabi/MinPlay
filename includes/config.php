<?php
require 'vendor/autoload.php';
ob_start(); // makes the php return its output only after finishing running
session_start();
date_default_timezone_set("Asia/Beirut");

try {
    $con = new MongoDB\Client();
    $con = $con->MinPlay;
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>