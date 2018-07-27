<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("credentials.php");
include ("functionSet.php");

$id = "";
$hasRequest = false;

//Check if the assistant is handling a request
if (count(handlingRequest($_SESSION['username'], $db_database, $db_username, $db_password, $db_host)) != 0) {
    echo "<script type='text/javascript'> alert('You are already handling a request!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
    exit(0);
}


//Find next item in queue
$rows = nextItemInQueue($_SESSION['username'], $db_database, $db_username, $db_password, $db_host);

if (count($rows) == 0) {
    echo "<script type='text/javascript'> alert('There is no available request in the queue now!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
    exit(0);
}

foreach ($rows as $row) {
    if (!is_null($row['Preference']) && $row['Preference'] != $_SESSION['username']) {

        //Query whether the request has been made for more than 10 minutes to get the request
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
                continue;
            } else {
                $hasRequest = true;
                break;
            }
        } catch (Exception $exception) {
            echo "<script type='text/javascript'> alert('Error when query whether the request has been made for more than 10 minutes to get the request!') </script>";
            exit(0);
        }


    } else {
        $id = $row['rID'];
        $hasRequest = true;
        break;
    }
}

if (!$hasRequest) {
    echo "<script type='text/javascript'> alert('There is no available request in the queue now!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
    exit(0);
}


//Change handling by
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("UPDATE Queue SET Handling_By = :handling_by WHERE rID = :id;");

    $stmt->bindParam(':handling_by', $_SESSION['username']);
    $stmt->bindParam(':id', $id);

    $stmt->execute();

} catch (Exception $exception) {
    echo "<script type='text/javascript'> alert('Error for changing handling by!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
    exit(0);
}

echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";