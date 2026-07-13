<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "evaluacomprainador";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else {
    // Connection successful
    // You can perform database operations here
    // echo "Connected successfully";
}