<?php
require 'vendor/autoload.php';
ob_start(); // makes the php return its output only after finishing running
session_start();
date_default_timezone_set("Asia/Beirut");

function qResult($q) {
    $result = Array();
    foreach ($q as $doc) {
        array_push($result, $doc);
    }
    return $result;
}

try {
    $con = new MongoDB\Client('mongodb+srv://pappa:ohh5UMa3caBAdozq@cluster0.q8o2d.mongodb.net/MinPlay?retryWrites=true&w=majority');
    $con = $con->MinPlay;
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>