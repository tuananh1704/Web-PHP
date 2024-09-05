<?php
require '../dbconnect.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Kiểm tra xem tên đăng nhập hoặc email đã tồn tại chưa
    $sql = "SELECT * FROM admin WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0) {
        // Tài khoản chưa có, thêm tài khoản vào
        $sql = "INSERT INTO admin (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $username, $password, $email);

        if($stmt -> execute()) {
            echo 'Tạo tài khoản admin thành công!';
        }
        else {
            echo "Lỗi: ".$conn->error;
        }

    }
    else {
        echo "Tên đănh nhập hoặc email đã tồn tại!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Register Admin</title>
</head>
<body>
    <div>
        <h1>Đăng ký tài khoản Admin</h1>
        <form method="POST" action="admin-register.php">
            <label for="username">Tên đăng nhập:</label><br>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Mật khẩu:</label><br>
            <input type="password" id="password" name="password" required><br>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            <button type="submit">Đăng ký</button>
            <hr>
            <p><a href="admin-login.php">Đăng nhập tài khoản admin</a></p>
        </form>
    </div>
</body>
</html>