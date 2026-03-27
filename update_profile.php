<?php
require_once 'config.php';

// Chỉ xử lý khi là request POST và user đã đăng nhập
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user'])) {
    $uid = $_SESSION['user']['id'];
    
    // 1. LẤY VÀ LÀM SẠCH KHOẢNG TRẮNG DỮ LIỆU
    // Dùng toán tử ?? để tránh lỗi "Undefined index"
    $fullname = trim($_POST['fullname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $new_email = trim($_POST['email'] ?? '');
    $gender = $_POST['gender'] ?? '';
    
    $day = $_POST['day'] ?? '';
    $month = $_POST['month'] ?? '';
    $year = $_POST['year'] ?? '';

    // 2. KIỂM TRA DỮ LIỆU BẰNG PHP (BACKEND VALIDATION)
    
    // Kiểm tra Họ tên: Chỉ cho phép chữ cái (hỗ trợ tiếng Việt) và khoảng trắng
    if (empty($fullname) || !preg_match("/^[\p{L}\s]+$/u", $fullname)) {
        die("<script>alert('Lỗi: Họ tên không hợp lệ. Vui lòng không nhập số hoặc ký tự đặc biệt!'); window.history.back();</script>");
    }

    // Kiểm tra Email: Phải đúng định dạng chuẩn
    if (!empty($new_email)) {
        $email_regex = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        if (!preg_match($email_regex, $new_email) || !filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            die("<script>alert('Lỗi: Định dạng Email không hợp lệ!'); window.history.back();</script>");
        }
    }

    // Kiểm tra Số điện thoại: Khớp với chuẩn frontend (Bắt đầu bằng 84 hoặc 03,05,07,08,09 và đủ 10-11 số)
    if (!empty($phone) && !preg_match("/^(84|0[3|5|7|8|9])[0-9]{8}$/", $phone)) {
        die("<script>alert('Lỗi: Số điện thoại không đúng định dạng!'); window.history.back();</script>");
    }

    // Kiểm tra Giới tính: Chỉ cho phép 'Nam' hoặc 'Nữ'
    $allowed_genders = ['Nam', 'Nữ'];
    if (!empty($gender) && !in_array($gender, $allowed_genders)) {
        die("<script>alert('Lỗi: Giới tính không hợp lệ!'); window.history.back();</script>");
    }

    // Kiểm tra Ngày sinh: Phải là ngày có thật (Tránh 31/02)
    $ngay_sinh = null; // Mặc định là null nếu không nhập
    if ($day !== '' && $month !== '' && $year !== '') {
        if (checkdate((int)$month, (int)$day, (int)$year)) {
            $ngay_sinh = "$year-$month-$day";
        } else {
            die("<script>alert('Lỗi: Ngày sinh không hợp lệ!'); window.history.back();</script>");
        }
    }

    // 3. LƯU VÀO DATABASE BẰNG PREPARED STATEMENT (CHỐNG SQL INJECTION 100%)
    $sql = "UPDATE users SET fullname = ?, phone = ?, email = ?, gioi_tinh = ?, ngay_sinh = ? WHERE id = ?";
    
    $stmt = $Conn->prepare($sql);
    
    if ($stmt) {
        // "sssssi" nghĩa là: String, String, String, String, String, Integer
        $stmt->bind_param("sssssi", $fullname, $phone, $new_email, $gender, $ngay_sinh, $uid);
        
        if ($stmt->execute()) {
            // Chỉ cập nhật Session khi Database đã lưu thành công
            $_SESSION['user']['fullname'] = $fullname;
            $_SESSION['user']['email'] = $new_email;
            
            echo "<script>alert('Cập nhật thành công!'); window.location.href='ThongTinTaiKhoan.php';</script>";
        } else {
            echo "Lỗi thực thi: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Lỗi chuẩn bị truy vấn: " . $Conn->error;
    }
} else {
    // Truy cập trái phép -> Đẩy về trang chủ
    header("Location: index.php");
    exit();
}
?>