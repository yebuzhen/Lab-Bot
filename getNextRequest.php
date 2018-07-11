<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("credentials.php");

$id = "";
//$mCode = '';
//$madeIn = '';

//date_default_timezone_set('Europe/London');
//
//$dateAndTime = new DateTime('now');
//
//$weekday = $dateAndTime->format('w');
//$time = $dateAndTime->format("H:i:s");
//$ifInModule = false;

////Query the module code
//try {
//    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;
//
//    $pdo = new PDO($dsn,$db_username,$db_password);
//
//    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
//
//    $stmt = $pdo->prepare("SELECT * FROM Labs WHERE (:time BETWEEN Start_Time AND End_Time) AND Weekday = :weekday;");
//
//    $stmt->bindParam(':time', $time);
//    $stmt->bindParam(':weekday', $weekday);
//
//    $stmt->execute();
//
//    $rows = $stmt->fetchAll();
//
//    if (count($rows) > 1) {
//        echo "<script type='text/javascript'> alert('There are more than 1 labs in the room, please contact admin!') </script>";;
//        exit(0);
//    }
//
//    foreach ($rows as $row) {
//        $mCode = $row['mCode'];
//    }
//
//    if ($mCode == 'null'){
//        echo "<script type='text/javascript'> alert('No lab now!') </script>";
//        exit(0);
//    }
//
//} catch (Exception $exception){
//    echo "<script type='text/javascript'> alert('Error for module code query!') </script>";
//    exit(0);
//}
//
//
////Check the enrollment
//try{
//    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;
//
//    $pdo = new PDO($dsn,$db_username,$db_password);
//
//    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
//
//    $stmt = $pdo->prepare("SELECT * FROM AdminEnrollment WHERE mCode = :code;");
//
//    $stmt->bindParam(':code', $mCode);
//
//    $stmt->execute();
//
//    $rows = $stmt->fetchAll();
//
//    foreach ($rows as $row) {
//        if ($row['aEmail'] == $_SESSION['username']) {
//            $ifInModule = true;
//        }
//    }
//
//    if (!$ifInModule) {
//        echo "<script type='text/javascript'> alert('You are not in this lab!') </script>";
//        exit(0);
//    }
//} catch (Exception $exception){
//    echo "<script type='text/javascript'> alert('Error for checking the enrollment!') </script>";
//    exit(0);
//}


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
        echo "<script type='text/javascript'> alert('You are already handling a request!') </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
        exit(0);
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