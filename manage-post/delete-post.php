<?php
session_start(); // Bắt đầu phiên làm việc

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require '../dbconnect.php';

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM posts WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $post_id, $user_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Bài đăng đã được xóa thành công!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Lỗi: " . $stmt->error;
        $_SESSION['message_type'] = "error";
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();

    // Chuyển hướng sau khi xử lý biểu mẫu để tránh việc gửi lại biểu mẫu
    header("Location: ../home.php");
    exit();
}
?>