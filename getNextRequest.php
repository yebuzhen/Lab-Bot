<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("credentials.php");

$id = "";
$null = 'null';

//Find next item in queue
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("SELECT * FROM Queue WHERE Handling_By = :handling_by ORDER BY Position;");

    $stmt->bindParam(':handling_by', $null);

    $stmt->execute();

    $rows = $stmt->fetchAll();

    if (count($rows) == 0) {
        echo "<script type='text/javascript'> alert('There is no available request in the queue now!') </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
        exit(0);
    }

    foreach ($rows as $row) {
        $id = $row['rID'];
        break;
    }
} catch (Exception $exception) {
    echo "<script type='text/javascript'> alert('Error for Find next item in queue!') </script>";
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