<?php
$servername = "localhost";
$username = "2405613";
$password = "2405613";
$dbname = "db2405613_teama";

//create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

//check the connection
if (!$conn) {
    die("Connection failed:".mysqli_connect_error());
}
echo "Connection successfully";
?>