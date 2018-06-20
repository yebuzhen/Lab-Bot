<?php
session_start();

if (!isset($_SESSION['username'])) {
    echo "<script type='text/javascript'> alert('Please log in first!') </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=studentLogin.php'>";
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    echo "<meta http-equiv='Refresh' content='0;URL=studentLogin.php'>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Request</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<style>
    table, th, td {
        border: 1px solid black;
    }
</style>

<body>

<div>
    Welcome <?php echo $_SESSION['username']?> !
</div>

<br>

<div id="first">
    <form action="onSending.php">
        <button id="request">Make a Request</button>
    </form>

    <form action="onCancelling.php">
        <button id="cancel">Cancel My Request</button>
    </form>

    <p id="requestSize"></p>
    <p id="queryPosition"></p>

    <table style="width: 100%;">
        <caption style="font-weight: bold; font-size: large;">Request History</caption>
        <tr>
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

            $stmt = $pdo->prepare("SELECT * FROM Requests WHERE Generated_by = :generated_by;");

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

    <p>
        <a href="student.php?logout='1'">logout</a>
    </p>
</div>

<script>
    function buttonChange(state) {
        if (state == false) {
            document.getElementById('request').disabled = true;
            document.getElementById('cancel').disabled = false;
        } else {
            document.getElementById('request').disabled = false;
            document.getElementById('cancel').disabled = true;
        }
    }

    function initial() {

        $.get("requestSize.php", function (size) {
            document.getElementById('requestSize').innerHTML = 'Waiting requests size (includes being handling ones): ' + size;
        });

        $.get("queryPosition.php", function (position) {
            if (position == -1) {
                document.getElementById("queryPosition").innerHTML = "You have not made a request.";
                buttonChange(true);
            } else if (position == -2) {
                document.getElementById("queryPosition").innerHTML = "Your request has been suspended, the assistant will help you out once ready.";
                buttonChange(false);
            } else if (position == 0){
                document.getElementById("queryPosition").innerHTML = "The assistant is coming.";
                buttonChange(false);
            } else{
                document.getElementById("queryPosition").innerHTML = "Your request is at position " + position + ". Waiting for assistants.";
                buttonChange(false);
            }
        });
    }

    initial();

    setInterval(initial, 1000);


</script>

</body>

</html>
