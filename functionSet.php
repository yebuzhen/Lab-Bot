<?php


function deleteQueueByID ($id, $operator, $db_database, $db_username, $db_password, $db_host) {

    try {
        $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

        $pdo = new PDO($dsn,$db_username,$db_password);

        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $stmt = $pdo->prepare("DELETE FROM Queue WHERE rID = :rID;");

        $stmt->bindParam(':rID', $id);

        $stmt->execute();

    } catch (Exception $exception){
        echo "<script type='text/javascript'> alert('Error queue deletion!') </script>";
        if ($operator == 'student') {
            echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
        } else {
            echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
        }
        exit(0);
    }

}

function deleteQueueByHandling_By ($handling_by, $operator, $db_database, $db_username, $db_password, $db_host) {

    try {
        $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

        $pdo = new PDO($dsn,$db_username,$db_password);

        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $stmt = $pdo->prepare("DELETE FROM Queue WHERE Handling_By = :handling_by;");

        $stmt->bindParam(':handling_by', $handling_by);

        $stmt->execute();

    } catch (Exception $exception){
        echo "<script type='text/javascript'> alert('Error queue deletion!') </script>";
        if ($operator == 'student') {
            echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
        } else {
            echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
        }
        exit(0);
    }

}

function decrementPosition($position, $operator, $db_database, $db_username, $db_password, $db_host) {

    try {
        $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

        $pdo = new PDO($dsn,$db_username,$db_password);

        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $stmt = $pdo->prepare("UPDATE Queue SET Position = Position - 1 WHERE Position > :position;");

        $stmt->bindParam(':position', $position);

        $stmt->execute();
    } catch (Exception $exception){
        echo "<script type='text/javascript'> alert('Error for position decrement!') </script>";
        if ($operator == 'student') {
            echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
        } else {
            echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
        }
        exit(0);
    }

}

function finishOneRequest ($id, $handled_by, $db_database, $db_username, $db_password, $db_host) {

    $state = 'Finished';

    try {
        $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

        $pdo = new PDO($dsn,$db_username,$db_password);

        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $stmt = $pdo->prepare("UPDATE Requests SET Handled_By = :handled_by, Finished_Time = NOW(), State = :state WHERE ID = :id;");

        $stmt->bindParam(':handled_by', $handled_by);
        $stmt->bindParam(':state', $state);
        $stmt->bindParam(':id', $id);

        $stmt->execute();
    } catch (Exception $exception){
        echo "<script type='text/javascript'> alert('Error for request state change!') </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
        exit(0);
    }

}

function checkLabValidity ($code, $db_database, $db_username, $db_password, $db_host) {
    date_default_timezone_set('Europe/London');
    $dateAndTime = new DateTime('now');
    $weekday = $dateAndTime->format('w');
    $time = $dateAndTime->format("H:i:s");

    //Query the current module code
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
            echo "<script type='text/javascript'> alert('There are more than 1 labs in the room, please contact admin!') </script>";
            echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
            exit(0);
        }

        foreach ($rows as $row) {
            if ($code == $row['mCode']) {
                return true;
            }
        }

    } catch (Exception $exception){
        echo "<script type='text/javascript'> alert('Error when check the current module code!') </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
        exit(0);
    }

    date_add($dateAndTime, date_interval_create_from_date_string('10 minutes'));
    $weekday = $dateAndTime->format('w');
    $time = $dateAndTime->format("H:i:s");

    //Check next 10 minutes lab
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
            if ($code == $row['mCode']) {
                return true;
            }
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
            if ($code == $row['mCode']) {
                return true;
            }
        }

    } catch (Exception $exception){
        echo "<script type='text/javascript'> alert('Error when module code query 15 minutes earlier!') </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
        exit(0);
    }

    return false;

}


function handlingRequest ($handling_by, $db_database, $db_username, $db_password, $db_host) {

    try {
        $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

        $pdo = new PDO($dsn,$db_username,$db_password);

        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $stmt = $pdo->prepare("SELECT * FROM Queue WHERE Handling_By = :handling_by;");

        $stmt->bindParam(':handling_by', $handling_by);

        $stmt->execute();

        $rows = $stmt->fetchAll();

        return $rows;

    } catch (Exception $exception) {
        echo "<script type='text/javascript'> alert('Error for checking if the assistant is handling a request!') </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
        exit(0);
    }

}


function nextItemInQueue ($email, $db_database, $db_username, $db_password, $db_host) {

    try {
        $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

        $pdo = new PDO($dsn,$db_username,$db_password);

        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $stmt = $pdo->prepare("SELECT * FROM Queue INNER JOIN Requests ON Requests.ID = Queue.rID INNER JOIN AdminEnrollment ON AdminEnrollment.mCode = Requests.Made_In AND AdminEnrollment.aEmail = :email WHERE Queue.Handling_By IS NULL ORDER BY Queue.Position;");

        $stmt->bindParam(':email', $email);

        $stmt->execute();

        $rows = $stmt->fetchAll();

        return $rows;

    } catch (Exception $exception) {
        echo "<script type='text/javascript'> alert('Error for Find next item in queue!') </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
        exit(0);
    }

}