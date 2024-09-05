<?php
$servername = "localhost";
$username_db = "root";
$password_db = "debian";
$dbname = "NCSC_database";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>