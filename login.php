<?php
session_start();    

$message = '';  // Lưu thông báo

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'dbconnect.php';

    // Lấy dữ liệu từ form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra thông tin đăng nhập
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        // Đăng nhập thành công
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $result->fetch_assoc()['id'];    // Lưu ID người dùng vào session
        $_SESSION['message'] = 'Đăng nhập thành công';

        // Chuyển hướng đến trang home
        header("Location: home.php");
        exit();
    }
    else {
        $_SESSION['message'] = 'Tên đăng nhập hoặc mật khẩu không chính xác';
    }

    // Đóng kết nối
    $conn->close();

    // Hiển thị thông báo từ session và xóa session
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div class="form-container">
        <form method="post" action="login.php">
            <h2>Đăng nhập tài khoản</h2>
            <?php
            if (!empty($message)) {
                echo "<div>$message</div>";
            }
            ?>
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <button type="submit">Đăng nhập</button>
            <hr>
            <p><a href="register.php">Đăng ký tài khoản</a></p>
        </form>
    </div>
</body>
</html>