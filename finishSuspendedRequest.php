<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("credentials.php");

$state = 'Finished';

//Change request state
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("UPDATE Requests SET Handled_By = :handled_by, Finished_Time = NOW(), State = :state WHERE ID = :id;");

    $stmt->bindParam(':handled_by', $_SESSION['username']);
    $stmt->bindParam(':state', $state);
    $stmt->bindParam(':id', $_GET['id']);

    $stmt->execute();
} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error for request state change!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
    exit(0);
}

echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";