<?php if (session_status() == PHP_SESSION_NONE) {session_start();}
// =======================
// KẾT NỐI DATABASE
// =======================

$Host = "localhost";      // Server MySQL (XAMPP mặc định là localhost)
$User = "root";           // User mặc định XAMPP
$Password = "";           // XAMPP mặc định không có mật khẩu
$Database = "GearShop";   // Tên database của m

// Tạo kết nối
$Conn = new mysqli($Host, $User, $Password, $Database);

// Kiểm tra lỗi kết nối
if ($Conn->connect_error) {
	die("❌ Kết nối database thất bại: " . $Conn->connect_error);
}

// =======================
// SET UTF-8 (TRÁNH LỖI TIẾNG VIỆT)
// =======================
$Conn->set_charset("utf8");
// =======================
// KHỞI TẠO SESSION
// =======================
// =======================
// (OPTIONAL) HÀM HỖ TRỢ
// =======================

// Hàm kiểm tra đăng nhập
function KiemTraDangNhap() {
	return isset($_SESSION['user']);
}

// Hàm kiểm tra admin
function LaAdmin() {
	return isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin';
}

?>