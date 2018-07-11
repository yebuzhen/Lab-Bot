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

$queueCount = 0;
$id = generateRandomString();
$mCode = 'null';
$state = 'Waiting';

function checkFuzzyLogic($db_database, $db_host, $db_username, $db_password) {
    $mCode = 'null';

    date_default_timezone_set('Europe/London');
    $dateAndTime = new DateTime('now');
    date_add($dateAndTime, date_interval_create_from_date_string('10 minutes'));
    $weekday = $dateAndTime->format('w');
    $time = $dateAndTime->format("H:i:s");

    //Query the module code after 10 minutes
    try {
        $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

        $pdo = new PDO($dsn,$db_username,$db_password);

        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $stmt = $pdo->prepare("SELECT * FROM Labs WHERE (:time BETWEEN Start_Time AND End_Time) AND Weekday = :weekday;");

        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':weekday', $weekday);

        $stmt->execute();

        $rows = $stmt->fetchAll();

        if (count($rows) > 1) {
            echo "<script type='text/javascript'> alert('There are more than 1 labs in the room 10 minutes later, please contact admin!') </script>";
            echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
            exit(0);
        }

        foreach ($rows as $row) {
            $mCode = $row['mCode'];
            return $mCode;
        }

    } catch (Exception $exception){
        echo "<script type='text/javascript'> alert('Error when module code query after 10 minutes!') </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
        exit(0);
    }

    date_add($dateAndTime, date_interval_create_from_date_string('-25 minutes'));
    $weekday = $dateAndTime->format('w');
    $time = $dateAndTime->format("H:i:s");

    //Query the module code before 15 minutes
    try {
        $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

        $pdo = new PDO($dsn,$db_username,$db_password);

        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $stmt = $pdo->prepare("SELECT * FROM Labs WHERE (:time BETWEEN Start_Time AND End_Time) AND Weekday = :weekday;");

        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':weekday', $weekday);

        $stmt->execute();

        $rows = $stmt->fetchAll();

        if (count($rows) > 1) {
            echo "<script type='text/javascript'> alert('There are more than 1 labs in the room 15 minutes earlier, please contact admin!') </script>";
            echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
            exit(0);
        }

        foreach ($rows as $row) {
            $mCode = $row['mCode'];
            return $mCode;
        }

    } catch (Exception $exception){
        echo "<script type='text/javascript'> alert('Error when module code query 15 minutes earlier!') </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
        exit(0);
    }

    return $mCode;
}

$mCode = checkFuzzyLogic($db_database, $db_host, $db_username, $db_password);

//request insertion
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("INSERT INTO Requests (ID, State, Made_In, Generated_By) VALUES (:id, :state, :made_in, :generated_by);");

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':state', $state);
    $stmt->bindParam(':made_in', $mCode);
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

    $stmt = $pdo->prepare("INSERT INTO Queue (Position, rID) VALUES (:position, :rID);");

    $stmt->bindParam(':position', $queueCount);
    $stmt->bindParam(':rID', $id);

    $stmt->execute();
} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error for queue insertion!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
    exit(0);
}

echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
