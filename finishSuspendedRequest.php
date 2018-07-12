<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("credentials.php");

$state = 'Finished';
$code = '';


//Query request state
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("SELECT * FROM Requests WHERE ID = :id;");

    $stmt->bindParam(':id', $_GET['id']);

    $stmt->execute();

    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        if ($row['State'] == 'Canceled') {
            echo "<script type='text/javascript'> alert('The request has been canceled by the student!') </script>";
            echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
            exit(0);
        } else if ($row['State'] == 'Finished') {
            echo "<script type='text/javascript'> alert('The request has been finished by other assistant!') </script>";
            echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
            exit(0);
        }

        $code = $row['Made_In'];
    }

} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error for ID and position query!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
    exit(0);
}

//Check enrollment

try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("SELECT * FROM AdminEnrollment WHERE aEmail = :email AND mCode = :code;");

    $stmt->bindParam(":email", $_SESSION['username']);
    $stmt->bindParam(":code", $code);

    $stmt->execute();

    $rows = $stmt->fetchAll();

    if (count($rows) == 0){
        echo "<script type='text/javascript'> alert('Sorry you cannot handle this request!') </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
        exit(0);
    }

} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error when search modules!') </script>";
    exit(0);
}


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