<?php
$servername = "localhost";
$username = "root";
$password = ""; // Use your MySQL password
$dbname = "contact_manager";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
