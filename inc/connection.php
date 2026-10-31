<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "miguelpu_evaluacomprainador";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else {
    // Connection successful
    // You can perform database operations here
    // echo "Connected successfully";
}