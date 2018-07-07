<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Testing</title>
</head>

<body>

<?php

date_default_timezone_set('Europe/London');

$time = new DateTime('now');

echo "<p>Time: ". $time->format("H:i:s")."</p>";

date_add($time, date_interval_create_from_date_string('15 minutes'));

echo "<p>Edited Time: ". $time->format("H:i:s")."</p>";

?>


</body>

</html>