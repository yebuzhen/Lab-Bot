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

$id = generateRandomString();
$waitingState = "Waiting";
$finishedState = 'Finished';
$queueCount = 0;
$code = 'G52OSC';
$assistant = 'ad@ad.com';
$position = -1;

try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("INSERT INTO Requests (ID, State, Made_In, Generated_By) VALUES (:id, :state, :made_in, :generated_by);");

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':state', $waitingState);
    $stmt->bindParam(':made_in', $code);
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

    $stmt->bindParam(':state', $waitingState);

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

//Find next item in queue
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("SELECT * FROM Queue INNER JOIN Requests ON Requests.ID = Queue.rID INNER JOIN AdminEnrollment ON AdminEnrollment.mCode = Requests.Made_In AND AdminEnrollment.aEmail = :email WHERE Queue.Handling_By IS NULL ORDER BY Queue.Position;");

    $stmt->bindParam(':email', $assistant);

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

    $stmt->bindParam(':handling_by', $assistant);
    $stmt->bindParam(':id', $id);

    $stmt->execute();

} catch (Exception $exception) {
    echo "<script type='text/javascript'> alert('Error for changing handling by!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
    exit(0);
}


//Query for ID and position
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("SELECT * FROM Queue WHERE Handling_By = :handling_by;");

    $stmt->bindParam(':handling_by', $_SESSION['username']);

    $stmt->execute();

    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        $id = $row['rID'];
        $position = $row['Position'];
    }

    if ($id == ""){
        echo "<script type='text/javascript'> alert('The request has been canceled by the student!') </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
        exit(0);
    }

} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error for ID and position query!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
    exit(0);
}


//Delete queue
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("DELETE FROM Queue WHERE Handling_By = :handling_by;");

    $stmt->bindParam(':handling_by', $assistant);

    $stmt->execute();

} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error queue deletion!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
    exit(0);
}


//Decrement position(s)
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("UPDATE Queue SET Position = Position - 1 WHERE Position > :position;");

    $stmt->bindParam(':position', $position);

    $stmt->execute();
} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error for position decrement!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
    exit(0);
}


//Change request state
try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("UPDATE Requests SET Handled_By = :handled_by, Finished_Time = NOW(), State = :state WHERE ID = :id;");

    $stmt->bindParam(':handled_by', $assistant);
    $stmt->bindParam(':state', $finishedState);
    $stmt->bindParam(':id', $id);

    $stmt->execute();
} catch (Exception $exception){
    echo "<script type='text/javascript'> alert('Error for request state change!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
    exit(0);
}