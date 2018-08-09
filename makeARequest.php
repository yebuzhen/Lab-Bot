<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("credentials.php");
include ("functionSet.php");

function generateRandomString($length = 11) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$id = generateRandomString();
$state = "Waiting";
$ifEnrolled = false;
$queueCount = 0;
$preference = '';


//Check request validity

if (!checkLabValidity ($_GET['code'], $db_database, $db_username, $db_password, $db_host)) {
    echo "<script type='text/javascript'> alert('The lab you request is no longer valid!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
    exit(0);
}


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

//Check enrollment
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("SELECT * FROM Enrollment WHERE mCode = :code;");

    $stmt->bindParam(':code', $_GET['code']);

    $stmt->execute();

    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        if ($row['uEmail'] == $_SESSION['username']) {
            $ifEnrolled = true;
        }
    }

} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error when check the requested lab is enrolled!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
    exit(0);
}

if ($ifEnrolled) {
    //request insertion
    try {
        $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

        $pdo = new PDO($dsn,$db_username,$db_password);

        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $stmt = $pdo->prepare("INSERT INTO Requests (ID, State, Made_In, Generated_By) VALUES (:id, :state, :made_in, :generated_by);");

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':state', $state);
        $stmt->bindParam(':made_in', $_GET['code']);
        $stmt->bindParam(':generated_by', $_SESSION['username']);

        $stmt->execute();
    } catch (Exception $exception){
        echo "<script type='text/javascript'> alert('Error for request insertion!') </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
        exit(0);
    }

    //Query preference
    date_default_timezone_set('Europe/London');
    $dateAndTime = new DateTime('now');
    date_add($dateAndTime, date_interval_create_from_date_string('-10 minutes'));
    $time = $dateAndTime->format("Y:m:d H:i:s");

    try {
        $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

        $pdo = new PDO($dsn,$db_username,$db_password);

        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $stmt = $pdo->prepare("SELECT * FROM Requests WHERE Generated_By = :generated_by AND Finished_Time > :time AND Made_In = :mCode ORDER BY Finished_Time DESC;");

        $stmt->bindParam(':generated_by', $_SESSION['username']);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':mCode', $_GET['code']);

        $stmt->execute();

        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {
            $preference = $row['Handled_By'];
            break;
        }

    } catch (Exception $exception){
        echo "<script type='text/javascript'> alert('Error when query preference!') </script>";
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

    if ($preference != ''){
        //queue insertion with preference
        try {
            $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

            $pdo = new PDO($dsn,$db_username,$db_password);

            $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

            $stmt = $pdo->prepare("INSERT INTO Queue (Position, rID, Preference) VALUES (:position, :rID, :preference);");

            $stmt->bindParam(':position', $queueCount);
            $stmt->bindParam(':rID', $id);
            $stmt->bindParam(':preference', $preference);

            $stmt->execute();
        } catch (Exception $exception){
            echo "<script type='text/javascript'> alert('Error when queue insertion with preference!') </script>";
            echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
            exit(0);
        }

    } else {
        //queue insertion without preference
        try {
            $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

            $pdo = new PDO($dsn,$db_username,$db_password);

            $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

            $stmt = $pdo->prepare("INSERT INTO Queue (Position, rID) VALUES (:position, :rID);");

            $stmt->bindParam(':position', $queueCount);
            $stmt->bindParam(':rID', $id);

            $stmt->execute();
        } catch (Exception $exception){
            echo "<script type='text/javascript'> alert('Error for queue insertion!') </script>";
            echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
            exit(0);
        }

    }

    echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";

} else {
    echo "<script type='text/javascript'> alert('You cannot request for this lab!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
    exit(0);
}