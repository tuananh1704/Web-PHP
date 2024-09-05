<?php
session_start();

// Kiểm tra admin đã đăng nhập chưa
if(!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

require "../dbconnect.php";

// Lấy danh sách người dùng và sắp xếp theo ID tăng dần
$sql = "SELECT * FROM users ORDER BY id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <script>
        // JavaScript để ẩn thông báo sau 3 giây
        function hideMessage() {
            setTimeout(function() {
                var messageDiv = document.getElementById("message");
                if (messageDiv) {
                    messageDiv.style.display = "none";
                }
            }, 3000);
        }
    </script>
</head>
<body onload="hideMessage()">
    <div class="container">
        <h1>Quản lý người dùng</h1>
        <a href="add-user.php">Thêm Người Dùng</a>

        <?php
        if (isset($_SESSION['message'])) {
            echo "<div>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']);
        }
        ?>

        <h2>Danh sách người dùng</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <form action="edit-user.php" method="POST">
                                <td><?php echo $row['id']; ?></td>
                                <td><input type="text" name="username" value="<?php echo $row['username']; ?>" required></td>
                                <td><input type="email" name="email" value="<?php echo $row['email']; ?>" required></td>
                                <td><input type="text" name="password" value="<?php echo $row['password']; ?>" required></td>
                                <td>
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit">Sửa</button>
                                </td>
                            </form>
                            <td>
                                <form action="delete-user.php" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Không có người dùng nào.</p>
        <?php endif; ?>

        <?php $conn->close(); ?>
    </div>
</body>
</html>