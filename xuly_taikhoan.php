<?php
session_start(); 
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Dùng ?? '' để tránh lỗi nếu người dùng không gửi biến này lên
    $hanh_dong = $_POST['loai_hanh_dong'] ?? '';

    // ==========================================
    // 1. XỬ LÝ ĐĂNG KÝ
    // ==========================================
    if ($hanh_dong == 'dang_ky') {
        $tai_khoan = trim($_POST['tai_khoan'] ?? '');
        $ho = trim($_POST['ho'] ?? '');
        $ten = trim($_POST['ten'] ?? '');
        $mat_khau = $_POST['mat_khau'] ?? ''; 
        
        // Kiểm tra trống
        if (empty($tai_khoan) || empty($ho) || empty($ten) || empty($mat_khau)) {
            die("<script>alert('Vui lòng điền đầy đủ thông tin!'); history.back();</script>");
        }

        // Kiểm tra Họ Tên không chứa số/ký tự đặc biệt
        if (!preg_match("/^[\p{L}\s]+$/u", $ho) || !preg_match("/^[\p{L}\s]+$/u", $ten)) {
            die("<script>alert('Họ hoặc Tên không hợp lệ!'); history.back();</script>");
        }

        // KIỂM TRA BẢO MẬT MẬT KHẨU (Ít nhất 8 ký tự, 1 chữ in hoa, 1 ký tự đặc biệt)
        if (!preg_match("/^(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}$/", $mat_khau)) {
            die("<script>alert('Lỗi: Mật khẩu phải có ít nhất 8 ký tự, bao gồm ít nhất 1 chữ in hoa và 1 ký tự đặc biệt!'); history.back();</script>");
        }

        // Kiểm tra Tài khoản phải là Email HOẶC Số điện thoại
        $email_regex = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        $phone_regex = "/^(84|0[3|5|7|8|9])+([0-9]{8})$/";
        
        if (!preg_match($email_regex, $tai_khoan) && !preg_match($phone_regex, $tai_khoan)) {
            die("<script>alert('Lỗi: Tài khoản phải là Email hoặc Số điện thoại hợp lệ!'); history.back();</script>");
        }

        $fullname = $ho . " " . $ten;
        $role = 'customer';

        // Phân loại tài khoản là email hay sđt 
        $email_luu = preg_match($email_regex, $tai_khoan) ? $tai_khoan : NULL;
        $phone_luu = preg_match($phone_regex, $tai_khoan) ? $tai_khoan : NULL;

        // Kiểm tra tài khoản đã tồn tại chưa
        $stmt_check = $Conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ? OR username = ?");
        $stmt_check->bind_param("sss", $tai_khoan, $tai_khoan, $tai_khoan);
        $stmt_check->execute();
        $stmt_check->store_result();
        
        if ($stmt_check->num_rows > 0) {
            echo "<script>alert('Tài khoản, Email hoặc Số điện thoại này đã tồn tại!'); history.back();</script>";
        } else {
            
            // LƯU MẬT KHẨU 
            $stmt_insert = $Conn->prepare("INSERT INTO users (username, password, fullname, email, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("ssssss", $tai_khoan, $mat_khau, $fullname, $email_luu, $phone_luu, $role);

            if ($stmt_insert->execute()) {
                echo "<script>alert('Đăng ký thành công! Hãy đăng nhập.'); window.location.href='index.php';</script>";
            } else {
                echo "Lỗi: " . $stmt_insert->error;
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }

    // ==========================================
    // 2. XỬ LÝ ĐĂNG NHẬP
    // ==========================================
    elseif ($hanh_dong == 'dang_nhap') {
        $tai_khoan = trim($_POST['tai_khoan'] ?? '');
        $mat_khau = $_POST['mat_khau'] ?? '';

        if (empty($tai_khoan) || empty($mat_khau)) {
            die("<script>alert('Vui lòng nhập tài khoản và mật khẩu!'); history.back();</script>");
        }

        // Tìm User trong Database
        $stmt_login = $Conn->prepare("SELECT * FROM users WHERE username = ? OR email = ? OR phone = ?");
        $stmt_login->bind_param("sss", $tai_khoan, $tai_khoan, $tai_khoan);
        $stmt_login->execute();
        $result = $stmt_login->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // SO SÁNH MẬT KHẨU TRỰC TIẾP 
            if ($mat_khau === $user['password']) {
                unset($user['password']); 
                $_SESSION['user'] = $user;
                echo "<script>
                alert('Đăng nhập thành công!');
                window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Sai tài khoản hoặc mật khẩu!'); history.back();</script>";
            }
        } else {
            echo "<script>alert('Sai tài khoản hoặc mật khẩu!'); history.back();</script>";
        }
        $stmt_login->close();
    }

    // ==========================================
    // 3. XỬ LÝ KHÔI PHỤC MẬT KHẨU
    // ==========================================
    elseif ($hanh_dong == 'quen_mat_khau') {
        $tai_khoan_khoi_phuc = trim($_POST['tai_khoan'] ?? '');

        if (empty($tai_khoan_khoi_phuc)) {
            die("<script>alert('Vui lòng nhập Email hoặc SĐT để khôi phục!'); history.back();</script>");
        }

        // Kiểm tra xem tài khoản (email hoặc sđt) này có tồn tại không
        $stmt_check = $Conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ? OR username = ?");
        $stmt_check->bind_param("sss", $tai_khoan_khoi_phuc, $tai_khoan_khoi_phuc, $tai_khoan_khoi_phuc);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $mat_khau_moi = "F1Gear_" . rand(10000, 99999);

            // Cập nhật pass mới không mã hóa
            $stmt_update = $Conn->prepare("UPDATE users SET password = ? WHERE email = ? OR phone = ? OR username = ?");
            $stmt_update->bind_param("ssss", $mat_khau_moi, $tai_khoan_khoi_phuc, $tai_khoan_khoi_phuc, $tai_khoan_khoi_phuc);
            
            if ($stmt_update->execute()) {
                echo "<script>
                    prompt('Thành công! Hãy COPY mật khẩu mới của bạn ở ô bên dưới rồi bấm OK:', '" . $mat_khau_moi . "'); 
                    window.location.href='index.php';
                </script>";
            } else {
                echo "<script>alert('Có lỗi xảy ra!'); history.back();</script>";
            }
            $stmt_update->close();
        } else {
            echo "<script>alert('Lỗi: Tài khoản này chưa được đăng ký trong hệ thống!'); history.back();</script>";
        }
        $stmt_check->close();
    }
}
?>