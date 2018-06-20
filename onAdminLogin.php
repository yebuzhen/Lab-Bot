<?php

function redirectFailure(){
    header("Location: adminLogin.php");
    exit(0);
}

session_start();

$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
if(! (filter_var($email, FILTER_VALIDATE_EMAIL) and strlen($email) < 255)){
    redirectFailure();
}

$pass = $_POST["password"];

$success = 0;

include('credentials.php');

try {

    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;

    $pdo = new PDO($dsn,$db_username,$db_password);

    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    $rows = $pdo->query("SELECT * FROM Admins;");

    foreach ($rows as $row) {
        if ($email == $row['Email'] && $pass == $row['Password']) {
            $success = 1;

            $_SESSION['username'] = $email;

            echo "<meta http-equiv='Refresh' content='0;URL=admin.php'>";
            exit(0);
        }
    }

    if ($success == 0){
        echo "<script type='text/javascript'> alert('Incorrect email or password, please login again.') </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=adminLogin.php'>";
        exit(0);
    }
}
catch (Exception $e) {
    redirectFailure();
}