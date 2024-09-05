<?php
session_start();
require '../dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $sql = "DELETE FROM users where id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if($stmt->execute()) {
        $_SESSION['message'] = "Xóa thành công!";
    }
    else {
        $_SESSION['message'] = "Error: ".$conn->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: admin-panel.php");
    exit();
}
?>