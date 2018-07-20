<?php
date_default_timezone_set('Europe/London');
$dateAndTime = new DateTime('now');
$time = $dateAndTime->format("Y:m:d H:i:s");

echo $time;

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Testing</title>
</head>

<body>

<p>Testing page here</p>


</body>

</html>