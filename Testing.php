<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Testing</title>
</head>

<body>

<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("credentials.php");

$null = 'null';
$email = 'ad@ad.com';

try {
    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $stmt = $pdo->prepare("SELECT * FROM Queue INNER JOIN Requests ON Requests.ID = Queue.rID INNER JOIN AdminEnrollment ON AdminEnrollment.mCode = Requests.Made_In AND AdminEnrollment.aEmail = :email WHERE Queue.Handling_By = :handling_by ORDER BY Queue.Position;");

    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':handling_by', $null);


    $stmt->execute();

    $rows = $stmt->fetchAll();

    if (count($rows) == 0) {
        echo 'null';
        exit(0);
    } else {
        echo 'Has request';
    }

    foreach ($rows as $row) {
        $id = $row['rID'];
        break;
    }
} catch (Exception $exception) {
    echo "<script type='text/javascript'> alert('Error for Find next item in queue!') </script>";
    exit(0);
}

?>


</body>

</html>