<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("credentials.php");

$id = "";


//Check if the assistant is handling a request
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("SELECT * FROM Queue WHERE Handling_By = :handling_by;");

    $stmt->bindParam(':handling_by', $_SESSION['username']);

    $stmt->execute();

    $rows = $stmt->fetchAll();

    if (count($rows) != 0) {
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
                echo $row['Generated_By'];
                exit(0);
            }

        } catch (Exception $exception) {
            echo "<script type='text/javascript'> alert('Error for query first position!') </script>";
            exit(0);
        }
    }

} catch (Exception $exception) {
    echo "<script type='text/javascript'> alert('Error for checking if the assistant is handling a request!') </script>";
    exit(0);
}

//Find next item in queue
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("SELECT * FROM Queue INNER JOIN Requests ON Requests.ID = Queue.rID INNER JOIN AdminEnrollment ON AdminEnrollment.mCode = Requests.Made_In AND AdminEnrollment.aEmail = :email WHERE Queue.Handling_By IS NULL ORDER BY Queue.Position;");

    $stmt->bindParam(':email', $_SESSION['username']);


    $stmt->execute();

    $rows = $stmt->fetchAll();

    if (count($rows) == 0) {
        echo 'null';
        exit(0);
    } else {
        echo 'Has request';
    }

} catch (Exception $exception) {
    echo "<script type='text/javascript'> alert('Error for Find next item in queue!') </script>";
    exit(0);
}