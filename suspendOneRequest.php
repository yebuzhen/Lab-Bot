<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("credentials.php");
include ("functionSet.php");

$id = "";
$state = 'Suspended';
$position = -1;

//Query for ID and position
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("SELECT * FROM Queue WHERE Handling_By = :handling_by;");

    $stmt->bindParam(':handling_by', $_SESSION['username']);

    $stmt->execute();

    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        $id = $row['rID'];
        $position = $row['Position'];
    }

    if ($id == ""){
        echo "<script type='text/javascript'> alert('The request has been canceled by the student!') </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
        exit(0);
    }

} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error for ID and position query!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
    exit(0);
}


//Delete queue
deleteQueueByHandling_By($_SESSION['username'], 'admin', $db_database, $db_username, $db_password, $db_host);


//Decrement position(s)
decrementPosition($position, "admin", $db_database, $db_username, $db_password, $db_host);


//Change request state
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("UPDATE Requests SET Handled_By = :handled_by, State = :state WHERE ID = :id;");

    $stmt->bindParam(':handled_by', $_SESSION['username']);
    $stmt->bindParam(':state', $state);
    $stmt->bindParam(':id', $id);

    $stmt->execute();
} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error for request state change!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
    exit(0);
}

echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";