<?php
session_start();

include('credentials.php');

$success = 0;

if (!isset($_SESSION['username'])) {
    echo "<script type='text/javascript'> alert('Please log in first!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=studentLogin.php'>";
    exit(0);
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    echo "<meta http-equiv='Refresh' content='0;URL=studentLogin.php'>";
    exit(0);
}

try {

    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $rows = $pdo->query("SELECT * FROM Users;");

    foreach ($rows as $row) {
        if ($row['Email'] == $_SESSION['username']) {
            $success = 1;
        }
    }

    if ($success == 0){
        echo "<script type='text/javascript'> alert('You are not a student.') </script>";
        session_destroy();
        unset($_SESSION['username']);
        echo "<meta http-equiv='Refresh' content='0;URL=studentLogin.php'>";
        exit(0);
    }
}
catch (Exception $e) {
    echo "<script type='text/javascript'> alert('Error when query if is a valid student.') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=studentLogin.php'>";
    exit(0);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="css/homepage.css">
</head>

<body>
    <div class="wrapper">

        <div>
            Welcome <?php echo $_SESSION['username']?> !
        </div>

        <p>
            <a href="student.php?logout='1'" style="color: #dddd55">logout</a>
        </p>

        <div id="first">

            <form action="onCancelling.php">
                <button id="cancel" class="btn btn-danger">Cancel My Request</button>
            </form>

            <p/>

            <table id="availableLabs" class="table-hover" style='width: 100%; border-color: white;' border="1">
                <caption style="font-weight: bold; font-size: large; caption-side: top; color: #dd5; text-align: center">Available Labs</caption>
                <tr style="color: #dddd55">
                    <th>Lab Title</th>
                    <th>Lab State</th>
                    <th>Action</th>
                </tr>
                <?php

                error_reporting(E_ALL);
                ini_set('display_errors', 1);

                include("credentials.php");

                date_default_timezone_set('Europe/London');

                $dateAndTime = new DateTime('now');

                $weekday = $dateAndTime->format('w');
                $time = $dateAndTime->format("H:i:s");

                $state = 'Waiting';
                $currentLabCode = 'null';
                $nearbyLabCode = 'null';

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
                        $currentLabCode = $row['mCode'];
                    }

                } catch (Exception $exception){
                    echo "<script type='text/javascript'> alert('Error when check the current module code!') </script>";
                    echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
                    exit(0);
                }

                //Check the current lab is enrolled
                try {
                    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

                    $pdo = new PDO($dsn,$db_username,$db_password);

                    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

                    $stmt = $pdo->prepare("SELECT * FROM Enrollment WHERE mCode = :code;");

                    $stmt->bindParam(':code', $currentLabCode);

                    $stmt->execute();

                    $rows = $stmt->fetchAll();

                    foreach ($rows as $row) {
                        if ($row['uEmail'] == $_SESSION['username']) {
                            echo "<tr><td>" . $currentLabCode . "</td><td>Ongoing</td>";
                            echo "<td><a href='makeARequest.php?code=" . $currentLabCode . "' style='color: #dddd55'>Request</a> </td></tr>";
                        }
                    }

                } catch (Exception $exception){
                    echo "<script type='text/javascript'> alert('Error when check the current lab is enrolled!') </script>";
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
                        $nearbyLabCode = $row['mCode'];
                    }

                } catch (Exception $exception){
                    echo "<script type='text/javascript'> alert('Error when module code query after 10 minutes!') </script>";
                    echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
                    exit(0);
                }

                if ($nearbyLabCode != $currentLabCode && $nearbyLabCode != 'null') {
                    //Check later lab is enrolled
                    try {
                        $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

                        $pdo = new PDO($dsn,$db_username,$db_password);

                        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

                        $stmt = $pdo->prepare("SELECT * FROM Enrollment WHERE mCode = :code;");

                        $stmt->bindParam(':code', $nearbyLabCode);

                        $stmt->execute();

                        $rows = $stmt->fetchAll();

                        foreach ($rows as $row) {
                            if ($row['uEmail'] == $_SESSION['username']) {
                                echo "<tr><td>" . $nearbyLabCode . "</td><td>About to Start</td>";
                                echo "<td><a href='makeARequest.php?code=" . $nearbyLabCode . "' style='color: #dddd55'>Request</a> </td></tr>";
                            }
                        }

                    } catch (Exception $exception){
                        echo "<script type='text/javascript'> alert('Error when check the later lab is enrolled!') </script>";
                        echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
                        exit(0);
                    }
                } else {
                    //Check last lab with 15 minutes

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
                            $nearbyLabCode = $row['mCode'];
                        }

                    } catch (Exception $exception){
                        echo "<script type='text/javascript'> alert('Error when module code query 15 minutes earlier!') </script>";
                        echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
                        exit(0);
                    }

                    if ($nearbyLabCode != 'null' && $nearbyLabCode != $currentLabCode) {
                        //Check last lab is enrolled

                        try {
                            $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

                            $pdo = new PDO($dsn,$db_username,$db_password);

                            $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

                            $stmt = $pdo->prepare("SELECT * FROM Enrollment WHERE mCode = :code;");

                            $stmt->bindParam(':code', $nearbyLabCode);

                            $stmt->execute();

                            $rows = $stmt->fetchAll();

                            foreach ($rows as $row) {
                                if ($row['uEmail'] == $_SESSION['username']) {
                                    echo "<tr><td>" . $nearbyLabCode . "</td><td>Just Over</td>";
                                    echo "<td><a href='makeARequest.php?code=" . $nearbyLabCode . "' style='color: #dddd55'>Request</a> </td></tr>";
                                }
                            }

                        } catch (Exception $exception){
                            echo "<script type='text/javascript'> alert('Error when check the last lab is enrolled!') </script>";
                            echo "<meta http-equiv='Refresh' content='0;URL=student.php'>";
                            exit(0);
                        }
                    }
                }


                ?>


            <p id="currentLab"></p>
            <p id="nextLab"></p>
            <p id="queryPosition"></p>

            <table id="requestHistory" class='table-hover' style='width: 100%; border-color: white;' border="1">
                <caption style="font-weight: bold; font-size: large; caption-side: top; color: #dd5; text-align: center">Request History</caption>
                <tr style="color: #dddd55">
                    <th>State</th>
                    <th>Created Time</th>
                    <th>Finished Time</th>
                </tr>
                <?php
                error_reporting(E_ALL);
                ini_set('display_errors', 1);

                include("credentials.php");

                try {
                    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

                    $pdo = new PDO($dsn,$db_username,$db_password);

                    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

                    $stmt = $pdo->prepare("SELECT * FROM Requests WHERE Generated_by = :generated_by ORDER BY Created_Time;");

                    $stmt->bindParam(':generated_by', $_SESSION['username']);

                    $stmt->execute();

                    $rows = $stmt->fetchAll();

                    foreach ($rows as $row) {
                        echo "<tr><td>" . $row['State'] . "</td><td>" . $row['Created_Time'] . "</td><td>" . $row['Finished_Time'] . "</td></tr>";
                    }

                } catch (Exception $exception){
                    echo "<script type='text/javascript'> alert('Error for search history query!') </script>";
                    exit(0);
                }
                ?>
            </table>
        </div>
    </div>
    <script>

        function initial() {

            $.get("queryPosition.php", function (position) {
                if (position == -1) {
                    document.getElementById("queryPosition").innerHTML = "You have not made a request.";
                    document.getElementById("cancel").outerHTML = "";
                } else if (position == -2) {
                    document.getElementById("queryPosition").innerHTML = "Your request has been suspended, the assistant will help you out once ready.";
                    document.getElementById("availableLabs").outerHTML = "";
                } else if (position == 0){
                    document.getElementById("queryPosition").innerHTML = "The assistant is coming.";
                    document.getElementById("availableLabs").outerHTML = "";
                } else{
                    document.getElementById("queryPosition").innerHTML = "Your request is at position " + position + ". Waiting for assistants.";
                    document.getElementById("availableLabs").outerHTML = "";
                }
            });

            $.get("currentLab.php", function (lab) {
                if (lab === 'duplicate labs') {
                    document.getElementById("currentLab").innerHTML = "There are more than 1 labs in the room, please contact admin!";
                } else if (lab === "no lab") {
                    document.getElementById("currentLab").innerHTML = "No lab now.";
                } else {
                    document.getElementById("currentLab").innerHTML = "Current lab is " + lab + ".";
                }
            });

            $.get("nextLab.php", function (lab) {
                if (lab === 'no lab after') {
                    document.getElementById("nextLab").innerHTML = "No more lab today.";
                } else {
                    document.getElementById("nextLab").innerHTML = "Next lab is " + lab + ".";
                }
            });
        }

        initial();

        setInterval(initial, 5000);


    </script>

</body>

</html>
