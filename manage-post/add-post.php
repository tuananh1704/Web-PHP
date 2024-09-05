<?php 
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    if($title != '' && $content != '') {
        $sql = "INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if($stmt === false) {
            die('Chuẩn bị thất bại: '.$conn->error);
        }
        $stmt->bind_param('ssi', $title, $content, $user_id);
        
        if($stmt->execute()) {
            // Thiết lập thông báo trong session
            $_SESSION['message'] = 'Bài viết mới được thêm thành công';
            header('Location: ../home.php');
            exit();
        } 
        else {
            echo "Lỗi: ".$stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
    else {
        echo 'Tiêu đề và nội dung không được để trống';
    }
}
else {
    echo "Không nhận được dữ liệu POST.";
}

?>