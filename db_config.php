<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "notes_app"; 


$conn = new mysqli($servername, $username, $password);


if ($conn->connect_errno) {
    echo "Database connection failed!<br>";
    echo "Reason: " . $conn->connect_error;
    exit();
}


$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
$qry = $conn->query($sql);

if ($qry) {
    echo "Database created successfully.";
} else {
    echo "Database has not been created!!<br>";
    echo "Reason: " . $conn->error;
    exit();
}


$conn->select_db($dbname);


$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(16) NOT NULL,
    email VARCHAR(100) NOT NULL
)";
$qry = $conn->query($sql);

if ($qry) {
    echo "<br/>Table 'users' created successfully.";
} else {
    echo "Table 'users' has not been created!!<br>";
    echo "Reason: " . $conn->error;
    exit();
}

?>
