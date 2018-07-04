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
            <form action="onSending.php" style="float: left">
                <button id="request" class="btn btn-success">Make a Request</button>
            </form>


            <form action="onCancelling.php">
                <button id="cancel" class="btn btn-danger" style="margin-left: 21%">Cancel My Request</button>
            </form>

            <p/>

            <p id="requestSize"></p>
            <p id="queryPosition"></p>

            <table class='table-hover' style='width: 100%;' border="1">
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

        setInterval(initial, 5000);


    </script>

</body>

</html>
