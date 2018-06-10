<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "staff";

$db = new mysqli($servername, $username, $password, $dbname);

if (!$db)
{
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}


function close_db()
{
	global $db;
	$db->close();
}