<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("credentials.php");

function generateRandomString($length = 11) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$state = 'Waiting';
$queueCount = 0;
$id = generateRandomString();
$handling_by = 'null';


//duplicate waiting or suspended query
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("SELECT State FROM Requests WHERE Generated_by = :generated_by;");

    $stmt->bindParam(':generated_by', $_SESSION['username']);

    $stmt->execute();

    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        if ($row['State'] == $state || $row['State'] == 'Suspended') {
            echo "<script type='text/javascript'> alert('Illegal request!') </script>";
            echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
            exit(0);
        }
    }

} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error for duplicate waiting query!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
    exit(0);
}


//request insertion
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("INSERT INTO Requests (ID, State, Generated_By) VALUES (:id, :state, :generated_by);");

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':state', $state);
    $stmt->bindParam(':generated_by', $_SESSION['username']);

    $stmt->execute();
} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error for request insertion!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
    exit(0);
}


//waiting requests size query
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("SELECT State FROM Requests WHERE State = :state;");

    $stmt->bindParam(':state', $state);

    $stmt->execute();

    $rows = $stmt->fetchAll();

    $queueCount = count($rows);

} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error for waiting requests size query!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
    exit(0);
}


//queue insertion
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("INSERT INTO Queue (Position, rID, Handling_By) VALUES (:position, :rID, :handling_by);");

    $stmt->bindParam(':position', $queueCount);
    $stmt->bindParam(':rID', $id);
    $stmt->bindParam(':handling_by', $handling_by);

    $stmt->execute();
} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error for queue insertion!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
    exit(0);
}

echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";