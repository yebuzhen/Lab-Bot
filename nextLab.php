<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("credentials.php");

date_default_timezone_set('Europe/London');

$dateAndTime = new DateTime('now');

$weekday = $dateAndTime->format('w');
$time = $dateAndTime->format("H:i:s");
$mCode = 'null';

//Query the next module code
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("SELECT * FROM Labs WHERE (Start_Time BETWEEN :time AND '23:59:59') AND Weekday = :weekday ORDER BY Start_Time;");

    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':weekday', $weekday);

    $stmt->execute();

    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        $mCode = $row['mCode'];
        break;
    }

    if ($mCode == 'null'){
        echo "no lab after";
        exit(0);
    }

    echo $mCode;
} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error when next module code query!') </script>";
    exit(0);
}