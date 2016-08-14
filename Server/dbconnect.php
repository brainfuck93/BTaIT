<?php
// Connect to DB
$connection = mysqli_connect("localhost","root","world147king","Industrie4.0");
if (mysqli_connect_errno()) {
    echo "MySQL Errror\n" . mysql_errno($connection) . ": " . mysql_error($connection). "\n";
    die();
}
?>
