<?php
require '../dbconnect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM posts WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo '<h1>' . $row['title'] . '</h1>';
        echo '<p>' . $row['content'] . '</p>';
    } else {
        echo '<p>Bài viết không tồn tại.</p>';
    }
} else {
    echo '<p>ID bài viết không hợp lệ.</p>';
}

mysqli_close($conn);
?>