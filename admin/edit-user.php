<?php
session_start();

// Kiểm tra admin đã đăng nhập chưa
if(!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}


require '../dbconnect.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $sql = "UPDATE users SET username = ?, password = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $username, $password, $email, $id);

    if($stmt->execute()) {
        $_SESSION['message'] = "Cập nhật thành công";
    }
    else {
        $_SESSION['message'] = "Lỗi: ". $conn->error();
    }

    $stmt->close();
    $conn->close();

    header("Location: admin-panel.php");
    exit();
}

?>