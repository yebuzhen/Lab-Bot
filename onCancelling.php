<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("credentials.php");
include ("functionSet.php");

$id = "";
$stateCanceled = 'Canceled';
$position = -1;
$ifSuspended = false;

//Query id
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("SELECT * FROM Requests WHERE Generated_By = :generated_by;");

    $stmt->bindParam(':generated_by', $_SESSION['username']);

    $stmt->execute();

    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        if ($row['State'] == 'Waiting'){
            $id = $row['ID'];
        } else if ($row['State'] == 'Suspended') {
            $id = $row['ID'];
            $ifSuspended = true;
        }
    }

    if ($id == ""){
        echo "<script type='text/javascript'> alert('Your request has been handled by the assistance!') </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
        exit(0);
    }

} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error for ID query!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
    exit(0);
}


//Change state
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("UPDATE Requests SET State = :state WHERE ID = :id;");

    $stmt->bindParam(':state', $stateCanceled);
    $stmt->bindParam(':id', $id);

    $stmt->execute();

} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error for state change!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
    exit(0);
}

//Check if suspended
if ($ifSuspended) {
    echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
    exit(0);
}

//Query position
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("SELECT * FROM Queue WHERE rID = :rID;");

    $stmt->bindParam(':rID', $id);

    $stmt->execute();

    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        $position = $row['Position'];
    }

} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error for position query!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
    exit(0);
}


deleteQueueByID($id, 'student', $db_database, $db_username, $db_password, $db_host);

decrementPosition($position, "student", $db_database, $db_username, $db_password, $db_host);


echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
