<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>


<body>

    <div class="wrapper">
        <form class="login" action="onAdminLogin.php" method="post">
            <p class="title">Log in (for assistants)</p>
            <input type="email" id="email" name="email" placeholder="Email" autofocus/>
            <i class="fa fa-user"></i>
            <input type="password" id="password" name="password" placeholder="Password" />
            <i class="fa fa-key"></i>
            <button type="submit">
                <i class="spinner"></i>
                <span class="state">Log in</span>
            </button>
        </form>
    </div>

</body>

</html>
