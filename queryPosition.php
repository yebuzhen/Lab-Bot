<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("credentials.php");

$id = '';

$_SESSION['requestPosition'] = -1;

//Query state
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("SELECT * FROM Requests WHERE Generated_by = :generated_by;");

    $stmt->bindParam(':generated_by', $_SESSION['username']);

    $stmt->execute();

    $rows = $stmt->fetchAll();

    $_SESSION['ifHasRequest'] = false;
    $_SESSION['ifSuspened'] = false;
    $_SESSION['request'] = null;

    foreach ($rows as $row) {

        if ($row['State'] == 'Waiting'){

            $id = $row['ID'];
            $_SESSION['ifHasRequest'] = true;
            $_SESSION['request'] = $row['ID'];
            $_SESSION['ifSuspened'] = false;

        } else if ($row['State'] == 'Suspended') {

            $_SESSION['ifHasRequest'] = true;
            $_SESSION['request'] = $row['ID'];
            $_SESSION['ifSuspened'] = true;
            echo -2;
            exit(0);

        }
    }

} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error for query state!') </script>";
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

    if (count($rows) == 0) {
        echo -1;
        exit(0);
    } else {
        foreach ($rows as $row) {
            if ($row['Handling_By'] == '') {
                $_SESSION['requestPosition'] = (int) $row['Position'];
                echo $row['Position'];
                exit(0);
            } else {
                $_SESSION['requestPosition'] = 0;
                echo 0;
                exit(0);
            }
        }
    }

} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error for query position!') </script>";
    exit(0);
}