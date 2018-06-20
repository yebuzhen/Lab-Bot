<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>


<body>
<p id="info">Login Please (for assistants)</p>
<form action="onAdminLogin.php" method="post">
    <input type="email" id="email" name="email" placeholder="Email"><br>
    <input type="password" id="password" name="password" placeholder="Password"><br>
    <input type="submit" value="Login">
</form>
<script>


</script>

</body>

</html>
