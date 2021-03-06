<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("credentials.php");
include ("functionSet.php");

$id = "";

$_SESSION['ifHandlingRequest'] = false;
$_SESSION['requestStudent'] = null;

//Check if the assistant is handling a request
$rows = handlingRequest($_SESSION['username'], $db_database, $db_username, $db_password, $db_host);

if (count($rows) != 0) {

    $_SESSION['ifHandlingRequest'] = true;

    foreach ($rows as $row) {
        $id = $row['rID'];
    }

    //Find generated_by
    try {
        $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

        $pdo = new PDO($dsn,$db_username,$db_password);

        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $stmt = $pdo->prepare("SELECT * FROM Requests WHERE ID = :id;");

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {
            $_SESSION['requestStudent'] = $row['Generated_By'];
            echo $row['Generated_By'];
            exit(0);
        }

    } catch (Exception $exception) {
        echo "<script type='text/javascript'> alert('Error for query first position!') </script>";
        exit(0);
    }
}

$rows = null;

//Find next item in queue
$rows = nextItemInQueue($_SESSION['username'], $db_database, $db_username, $db_password, $db_host);

if (count($rows) == 0) {
    echo 'null';
    exit(0);
}

foreach ($rows as $row) {
    //The request has a preference and is not the current assistant.
    if (!is_null($row['Preference']) && $row['Preference'] != $_SESSION['username']) {

        //Query whether the request has been made for more than 10 minutes
        $id = $row['rID'];

        date_default_timezone_set('Europe/London');
        $dateAndTime = new DateTime('now');
        date_add($dateAndTime, date_interval_create_from_date_string('-10 minutes'));
        $time = $dateAndTime->format("Y:m:d H:i:s");

        try {
            $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

            $pdo = new PDO($dsn,$db_username,$db_password);

            $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

            $stmt = $pdo->prepare("SELECT * FROM Requests WHERE ID = :id AND Created_Time < :time;");

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':time', $time);

            $stmt->execute();

            $rows = $stmt->fetchAll();

            if (count($rows) == 0) {
                //Within 10 minutes, cannot get that request.
                continue;
            } else {
                echo 'Has request';
                exit(0);
            }
        } catch (Exception $exception) {
            echo "<script type='text/javascript'> alert('Error when query whether the request has been made for more than 10 minutes!') </script>";
            exit(0);
        }


    } else { //No preference or the preference is the current assistant.
        echo 'Has request';
        exit(0);
    }
}

echo 'null';
exit(0);