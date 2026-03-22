<?php
// Nạp file cấu hình để có biến $Conn và Session
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy loại hành động (dang_nhap, dang_ky, hoặc quen_mat_khau)
    $hanh_dong = $_POST['loai_hanh_dong'];

    // --- TRƯỜNG HỢP: ĐĂNG KÝ ---
    if ($hanh_dong == 'dang_ky') {
        $tai_khoan = $Conn->real_escape_string($_POST['tai_khoan']);
        $ho = $Conn->real_escape_string($_POST['ho']);
        $ten = $Conn->real_escape_string($_POST['ten']);
        $mat_khau = $_POST['mat_khau']; 
        
        $fullname = $ho . " " . $ten;
        $role = 'customer';

        // Kiểm tra xem tài khoản đã tồn tại chưa
        $check = $Conn->query("SELECT id FROM users WHERE email = '$tai_khoan' OR username = '$tai_khoan'");
        
        if ($check->num_rows > 0) {
            echo "<script>alert('Tài khoản này đã tồn tại!'); history.back();</script>";
        } else {
            $sql = "INSERT INTO users (username, password, fullname, email, role) 
                    VALUES ('$tai_khoan', '$mat_khau', '$fullname', '$tai_khoan', '$role')";

            if ($Conn->query($sql)) {
                echo "<script>alert('Đăng ký thành công! Hãy đăng nhập.'); window.location.href='index.php';</script>";
            } else {
                echo "Lỗi: " . $Conn->error;
            }
        }
    }

    // --- TRƯỜNG HỢP: ĐĂNG NHẬP ---
    if ($hanh_dong == 'dang_nhap') {
        $tai_khoan = $Conn->real_escape_string($_POST['tai_khoan']);
        $mat_khau = $Conn->real_escape_string($_POST['mat_khau']);

        // Kiểm tra trùng khớp với Username HOẶC Email HOẶC Số điện thoại
        $sql = "SELECT * FROM users WHERE (username = '$tai_khoan' OR email = '$tai_khoan' OR phone = '$tai_khoan') AND password = '$mat_khau'";
        
        $result = $Conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Lưu thông tin vào SESSION
            $_SESSION['user'] = $user;

            echo "<script>alert('Chào mừng " . $user['fullname'] . " trở lại!'); window.location.href='index.php';</script>";
        } else {
            // Nếu sai, báo lỗi
            echo "<script>alert('Sai tài khoản hoặc mật khẩu!'); history.back();</script>";
        }
    }
}
?>