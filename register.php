<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$message = '';  // Lưu trữ thông báo

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'dbconnect.php';

    // Lấy dữ liệu lúc điền form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Kiểm tra xem username và email đã tồn tại chưa
    $check_user_query = 'SELECT * FROM users WHERE username = ? OR email = ?';
    $stmt = $conn->prepare($check_user_query);
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $_SESSION['message'] = 'Người dùng hoặc email đã tồn tại';
    }
    else {
        // Thêm người dùng mới vào CSDL
        $sql = 'INSERT INTO users (username, password, email) VALUES (?, ?, ?)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $username, $password, $email);
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Đăng ký tài khoản thành công';
        }
        else {
            $_SESSION['message'] = "Lỗi: ".$stmt->error;
        }
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();

    // Chuyển hướng sau khi xử lý biểu mẫu
    header("Location: register.php");
    exit();
}

// Hiển thị thông báo từ session và xóa session
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <div class="form-container">
        <form method="post" action="register.php">
            <h2>Đăng ký tài khoản</h2>
            <?php
            if (!empty($message)) {
                echo "<div>$message</div>";
            }
            ?>
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            <button type="submit">Đăng ký</button>
            <hr>
            <p><a href="login.php">Đăng nhập tài khoản</a></p>
        </form>
    </div>
</body>
</html>