<?php
// admin_funs.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Kết nối đến Database 'banhang'
$conn = mysqli_connect("localhost", "root", "", "banhang");
mysqli_set_charset($conn, "utf8");

if (!$conn) {
    die("Lỗi kết nối CSDL: " . mysqli_connect_error());
}

// Kiểm tra đăng nhập
function checkLogin($username, $password) {
    global $conn;
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);
    
    $sql = "SELECT * FROM taikhoan WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return false;
}
?>