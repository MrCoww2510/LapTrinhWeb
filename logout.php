<?php
session_start(); // Khởi tạo session để có quyền truy cập
session_unset(); // Giải phóng tất cả các biến session
session_destroy(); // Hủy bỏ toàn bộ session

// Chuyển hướng người dùng về trang chủ sau khi đăng xuất
header("Location: index.php");
exit();
?>