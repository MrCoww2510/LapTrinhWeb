<?php if (session_status() == PHP_SESSION_NONE) {session_start();}

// KẾT NỐI DATABASE


$Host = "localhost";
$User = "root";
$Password = "";
$Database = "GearShop";

// Tạo kết nối
$Conn = new mysqli($Host, $User, $Password, $Database);

// Kiểm tra lỗi kết nối
if ($Conn->connect_error) {
	die("❌ Kết nối database thất bại: " . $Conn->connect_error);
}

$Conn->set_charset("utf8");


// Hàm kiểm tra đăng nhập
function KiemTraDangNhap() {
	return isset($_SESSION['user']);
}

// Hàm kiểm tra admin
function LaAdmin() {
	return isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin';
}

// tránh lỗi session
?>