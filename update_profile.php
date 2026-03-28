<?php
session_start();
require_once 'config.php';

// Chỉ xử lý khi là request POST và user đã đăng nhập
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user'])) {
    $uid = $_SESSION['user']['id'];
    
    // 1. LẤY VÀ LÀM SẠCH KHOẢNG TRẮNG DỮ LIỆU
    $fullname = trim($_POST['fullname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $new_email = trim($_POST['email'] ?? '');
    $gender = $_POST['gender'] ?? '';
    
    $day = $_POST['day'] ?? '';
    $month = $_POST['month'] ?? '';
    $year = $_POST['year'] ?? '';

    // Lấy mật khẩu mới từ form
    $new_password = $_POST['new_password'] ?? '';

    // 2. KIỂM TRA DỮ LIỆU BẰNG PHP
    
    // Kiểm tra Họ tên: Chỉ cho phép chữ cái (hỗ trợ tiếng Việt) và khoảng trắng
    if (empty($fullname) || !preg_match("/^[\p{L}\s]+$/u", $fullname)) {
        die("<script>alert('Lỗi: Họ tên không hợp lệ. Vui lòng không nhập số hoặc ký tự đặc biệt!'); window.history.back();</script>");
    }

    // Kiểm tra Email: 
    if (!empty($new_email)) {
        $email_regex = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        if (!preg_match($email_regex, $new_email) || !filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            die("<script>alert('Lỗi: Định dạng Email không hợp lệ!'); window.history.back();</script>");
        }
    }

    // Kiểm tra Số điện thoại:
    if (!empty($phone) && !preg_match("/^(84|0[3|5|7|8|9])[0-9]{8}$/", $phone)) {
        die("<script>alert('Lỗi: Số điện thoại không đúng định dạng!'); window.history.back();</script>");
    }

    // Kiểm tra Giới tính: 
    $allowed_genders = ['Nam', 'Nữ'];
    if (!empty($gender) && !in_array($gender, $allowed_genders)) {
        die("<script>alert('Lỗi: Giới tính không hợp lệ!'); window.history.back();</script>");
    }

    // Kiểm tra Ngày sinh: Phải là ngày có thật (Tránh 31/02)
    $ngay_sinh = null; 
    if ($day !== '' && $month !== '' && $year !== '') {
        if (checkdate((int)$month, (int)$day, (int)$year)) {
            $ngay_sinh = "$year-$month-$day";
        } else {
            die("<script>alert('Lỗi: Ngày sinh không hợp lệ!'); window.history.back();</script>");
        }
    }

    if (!empty($new_password)) {
        // (?=.*[A-Z]): ít nhất 1 chữ hoa
        // (?=.*[^a-zA-Z0-9]): ít nhất 1 ký tự đặc biệt 
        // .{8,}: tổng độ dài ít nhất 8
        if (!preg_match("/^(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}$/", $new_password)) {
            die("<script>alert('Lỗi: Mật khẩu mới phải có ít nhất 8 ký tự, bao gồm ít nhất 1 chữ in hoa và 1 ký tự đặc biệt!'); window.history.back();</script>");
        }
    }

    // 3. LƯU VÀO DATABASE BẰNG PREPARED STATEMENT 
    
    if (!empty($new_password)) {
        // 1: Người dùng đổi mật khẩu
        $hashed_password = $new_password;   
        $sql = "UPDATE users SET fullname = ?, phone = ?, email = ?, gioi_tinh = ?, ngay_sinh = ?, password = ? WHERE id = ?";
        $stmt = $Conn->prepare($sql);
        
        if ($stmt) {
            // "ssssssi": 6 chuỗi (String) + 1 số (Integer - cho ID)
            $stmt->bind_param("ssssssi", $fullname, $phone, $new_email, $gender, $ngay_sinh, $hashed_password, $uid);
        }
    } else {
        // 2: Người dùng không đổi mật khẩu 
        $sql = "UPDATE users SET fullname = ?, phone = ?, email = ?, gioi_tinh = ?, ngay_sinh = ? WHERE id = ?";
        $stmt = $Conn->prepare($sql);
        
        if ($stmt) {
            // "sssssi": 5 chuỗi (String) + 1 số (Integer - cho ID)
            $stmt->bind_param("sssssi", $fullname, $phone, $new_email, $gender, $ngay_sinh, $uid);
        }
    }
    
    // 4. THỰC THI VÀ TRẢ VỀ THÔNG BÁO
    if ($stmt) {
        if ($stmt->execute()) {
            $_SESSION['user']['fullname'] = $fullname;
            $_SESSION['user']['email'] = $new_email;
            $msg = !empty($new_password) ? 'Cập nhật thông tin và mật khẩu thành công!' : 'Cập nhật thông tin thành công!';
            
            echo "<script>alert('$msg'); window.location.href='ThongTinTaiKhoan.php';</script>";
        } else {
            echo "<script>alert('Lỗi thực thi: " . $stmt->error . "'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Lỗi chuẩn bị truy vấn DB'); window.history.back();</script>";
    }
} else {
    
    header("Location: index.php");
    exit();
}
?>