<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$_SESSION['username'] = '11@11.com';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Load Test</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>

    <button id="endButton" onclick="end()">End</button>

    <div id="testingContent"></div>

</body>

<script>
    var studentQueryPositionCounter = 0;
    var studentCurrentLabCounter = 0;
    var studentNextLabCounter = 0;
    var assistantNextRequestCounter = 0;
    var assistantRequestSizeCounter = 0;
    var assistantCurrentLabCounter = 0;
    var assistantNextLabCounter = 0;

    function end() {
        clearInterval(studentQueryInterval);
        clearInterval(assistantQueryInterval);
        clearInterval(insertionAndFinishingInterval);
    }

    function studentQueryTest() {

        $.get("queryPosition.php", function () {
            if (studentQueryPositionCounter == 100) {
                document.getElementById("testingContent").innerHTML += "Student Position Query 100 Times! <br>";
                studentQueryPositionCounter = 0;
            } else {
                studentQueryPositionCounter++;
            }
        });

        $.get("currentLab.php", function () {
            if (studentCurrentLabCounter == 100) {
                document.getElementById("testingContent").innerHTML += "Student Current Lab Query 100 Times! <br>";
                studentCurrentLabCounter = 0;
            } else {
                studentCurrentLabCounter++;
            }
        });

        $.get("nextLab.php", function () {
            if (studentNextLabCounter == 100) {
                document.getElementById("testingContent").innerHTML += "Student Next Lab Query 100 Times! <br>";
                studentNextLabCounter = 0;
            } else {
                studentNextLabCounter++;
            }
        });

    }

    function assistantQueryTest() {

        $.get("nextRequest.php", function () {
            if (assistantNextRequestCounter == 5) {
                document.getElementById("testingContent").innerHTML += "Assistant Next Request Query 5 Times! <br>";
                assistantNextRequestCounter = 0;
            } else {
                assistantNextRequestCounter++;
            }
        });

        $.get("requestSize.php", function () {
            if (assistantRequestSizeCounter == 5) {
                document.getElementById("testingContent").innerHTML += "Assistant Request Size Query 5 Times! <br>";
                assistantRequestSizeCounter = 0;
            } else {
                assistantRequestSizeCounter++;
            }
        });

        $.get("currentLab.php", function () {
            if (assistantCurrentLabCounter == 5) {
                document.getElementById("testingContent").innerHTML += "Assistant Current Lab Query 5 Times! <br>";
                assistantCurrentLabCounter = 0;
            } else {
                assistantCurrentLabCounter++;
            }
        });

        $.get("nextLab.php", function () {
            if (assistantNextLabCounter == 5) {
                document.getElementById("testingContent").innerHTML += "Assistant Next Lab Query 5 Times! <br>";
                assistantNextLabCounter = 0;
            } else {
                assistantNextLabCounter++;
            }
        });

    }

    function insertionAndFinishingTest() {
        $.get("requestInsertionAndFinishingTest.php", function () {
            document.getElementById("testingContent").innerHTML += "Request Made and Finished! <br>";
        })
    }

    studentQueryTest();
    assistantQueryTest();
    insertionAndFinishingTest();

    var studentQueryInterval = setInterval(studentQueryTest, 50);
    var assistantQueryInterval = setInterval(assistantQueryTest, 1000);
    var insertionAndFinishingInterval = setInterval(insertionAndFinishingTest, 60000);

</script>