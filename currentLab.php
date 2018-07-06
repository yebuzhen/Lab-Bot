<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("credentials.php");

date_default_timezone_set('Europe/London');

$weekday = date("w");
$time = date("H:i:s");
$mCode = 'null';

//Query the module code
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("SELECT * FROM Labs WHERE (:time BETWEEN Start_Time AND End_Time) AND Weekday = :weekday;");

    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':weekday', $weekday);

    $stmt->execute();

    $rows = $stmt->fetchAll();

    if (count($rows) > 1) {
        echo "duplicate labs";
        exit(0);
    }

    foreach ($rows as $row) {
        $mCode = $row['mCode'];
    }

    if ($mCode == 'null'){
        echo "no lab";
        exit(0);
    }

    echo $mCode;
} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error for module code query!') </script>";
    exit(0);
}