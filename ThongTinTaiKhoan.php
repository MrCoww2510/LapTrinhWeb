<?php
require_once 'config.php'; 

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// 2. Lấy thông tin mới nhất từ Database
$u = $_SESSION['user'];
$userId = $u['id'];
$sql = "SELECT * FROM users WHERE id = $userId";
$result = $Conn->query($sql);
$userInfo = $result->fetch_assoc();

// 3. Xử lý tách ngày sinh
$bd_day = $bd_month = $bd_year = "";
if(!empty($userInfo['ngay_sinh'])) {
    $date_parts = explode('-', $userInfo['ngay_sinh']);
    $bd_year = $date_parts[0];
    $bd_month = (int)$date_parts[1];
    $bd_day = (int)$date_parts[2];
}

// 4. Tránh lỗi Warning cho Giới tính
$current_gender = isset($userInfo['gioi_tinh']) ? $userInfo['gioi_tinh'] : '';
$sql_address = "SELECT * FROM user_addresses WHERE user_id = $userId ORDER BY id DESC";
$result_address = $Conn->query($sql_address);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản - F1GamingGear</title>
    <link rel="stylesheet" href="css/ThongTinTaiKhoan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    /* CSS cho nút xóa địa chỉ */
    .btn-delete-address {
        padding: 5px;
        cursor: pointer;
        font-size: 1.2rem;
        transition: all 0.2s;
        color: #a0a0a0;
    }

    .btn-delete-address:hover {
        color: #e62e2e;
        transform: scale(1.1);
    }
    </style>
</head>

<body>

    <?php include("header.php"); ?>

    <div class="TTTK_container">
        <aside class="TTTK_sidebar">
            <div class="TTTK_user_info">
                <div class="TTTK_avatar"><i class="fas fa-user"></i></div>
                <span class="TTTK_username"><?php echo htmlspecialchars($userInfo['fullname']); ?></span>
            </div>
            <ul class="TTTK_menu_list">
                <li class="TTTK_menu_item TTTK_active" onclick="TTTK_switchTab(event, 'tab_profile')">
                    <i class="fas fa-user-circle"></i> Thông tin tài khoản
                </li>
                <li class="TTTK_menu_item" onclick="TTTK_switchTab(event, 'tab_address')">
                    <i class="fas fa-map-marker-alt"></i> Sổ địa chỉ
                </li>
                <li class="TTTK_menu_item" onclick="location.href='logout.php'">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </li>
            </ul>
        </aside>

        <main class="TTTK_main_content">

            <section id="tab_profile" class="TTTK_section TTTK_show">
                <h2 class="TTTK_title">Thông tin tài khoản</h2>
                <form action="update_profile.php" method="POST" style="margin-top: 30px;"
                    onsubmit="return validateProfileForm()">

                    <div class="TTTK_form_group">
                        <label class="TTTK_label">Họ Tên</label>
                        <input type="text" name="fullname" class="TTTK_input"
                            value="<?php echo htmlspecialchars($userInfo['fullname']); ?>" required>
                    </div>
                    <div class="TTTK_form_group">
                        <label class="TTTK_label">Giới tính</label>
                        <div class="TTTK_radio_group">
                            <label>
                                <input type="radio" name="gender" value="Nam"
                                    <?php echo ($current_gender == 'Nam') ? 'checked' : ''; ?>> Nam
                            </label>
                            <label>
                                <input type="radio" name="gender" value="Nữ"
                                    <?php echo ($current_gender == 'Nữ') ? 'checked' : ''; ?>> Nữ
                            </label>
                        </div>
                    </div>
                    
                    <div class="TTTK_form_group">
                        <label class="TTTK_label">Số điện thoại</label>
                        <input type="tel" name="phone" class="TTTK_input"
                            value="<?php echo isset($userInfo['phone']) ? htmlspecialchars($userInfo['phone']) : ''; ?>"
                            placeholder="Nhập số điện thoại" pattern="^(84|0[3|5|7|8|9])[0-9]{8}$"
                            title="Vui lòng nhập đúng số điện thoại hợp lệ (10 số)">
                    </div>

                    <div class="TTTK_form_group">
                        <label class="TTTK_label">Email</label>
                        <input type="email" name="email" class="TTTK_input"
                            value="<?php echo htmlspecialchars($userInfo['email']); ?>">
                    </div>

                    <div class="TTTK_form_group">
                        <label class="TTTK_label">Ngày sinh</label>
                        <div class="TTTK_select_group">
                            <select name="day" class="TTTK_select">
                                <option value="">Ngày</option>
                                <?php for($i=1;$i<=31;$i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($i == $bd_day) ? "selected" : ""; ?>>
                                    <?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                            <select name="month" class="TTTK_select">
                                <option value="">Tháng</option>
                                <?php for($i=1;$i<=12;$i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($i == $bd_month) ? "selected" : ""; ?>>
                                    Tháng <?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                            <select name="year" class="TTTK_select">
                                <option value="">Năm</option>
                                <?php for($i=date("Y");$i>=1950;$i--): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($i == $bd_year) ? "selected" : ""; ?>>
                                    <?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="TTTK_form_group">
                        <label class="TTTK_label">Đổi mật khẩu </label>
                        <input type="password" name="new_password" class="TTTK_input" placeholder="Nhập mật khẩu mới ">
                    </div>

                    <button type="submit" class="TTTK_btn_save">LƯU THAY ĐỔI</button>
                </form>
            </section>

            <section id="tab_address" class="TTTK_section">
                <div class="TTTK_header_row">
                    <h2 class="TTTK_title">Sổ địa chỉ</h2>
                    <button type="button" class="TTTK_btn_add_addr" onclick="TTTK_openModal()">+ Thêm địa chỉ
                        mới</button>
                </div>

                <div style="margin-top: 30px;">
                    <?php
                // Kiểm tra xem có địa chỉ nào không
                if ($result_address && $result_address->num_rows > 0) {
                    // Dùng vòng lặp in ra tất cả các địa chỉ đã lưu
                    while($addr = $result_address->fetch_assoc()) {
                ?>
                    <div class="address-item"
                        style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px; background-color: #f9f9f9; display: flex; justify-content: space-between; align-items: center;">

                        <div class="address-text">
                            <div style="font-size: 16px; margin-bottom: 8px;">
                                <strong
                                    style="color: #333; font-size: 18px;"><?php echo htmlspecialchars($addr['ho_ten']); ?></strong>
                                <span style="color: #999; margin: 0 10px;">|</span>
                                <span
                                    style="color: #555;"><?php echo htmlspecialchars($addr['so_dien_thoai']); ?></span>
                            </div>
                            <div style="color: #666; line-height: 1.5;">
                                <?php echo nl2br(htmlspecialchars($addr['dia_chi_day_du'])); ?>
                            </div>
                        </div>

                        <a href="javascript:void(0)" class="btn-delete-address text-danger"
                            data-id="<?php echo $addr['id']; ?>" onclick="xoaDiachi(this)" title="Xóa địa chỉ">
                            <i class="fa fa-trash-alt"></i>
                        </a>

                    </div>
                    <?php
                    } // Kết thúc vòng lặp
                } else {
                    // Nếu chưa có địa chỉ nào thì hiện dòng thông báo này
                ?>
                    <div style="color: #999; margin-top: 50px; text-align: center;">
                        <p>Bạn chưa có địa chỉ nào trong sổ địa chỉ.</p>
                    </div>
                    <?php } ?>
                </div>
            </section>

        </main>
    </div>

    <div class="TTTK_modal_overlay" id="TTTK_AddressModal" style="display: none;">
        <div class="TTTK_modal_box">
            <div class="TTTK_modal_header">
                <span>ĐỊA CHỈ MỚI</span>
                <span style="cursor:pointer; font-size:24px;" onclick="TTTK_closeModal()">&times;</span>
            </div>

            <form class="TTTK_modal_body" action="save_address.php" method="POST"
                onsubmit="return validateAddressForm()">
                <div style="margin-bottom: 15px;">
                    <p class="TTTK_modal_sub" style="margin-bottom: 5px;">Thông tin khách hàng</p>
                    <input type="text" name="addr_fullname" placeholder="Nhập Họ Tên" class="TTTK_modal_input"
                        style="width: 100%; margin-bottom: 10px;" required>
                    <input type="tel" name="addr_phone" placeholder="Nhập Số điện thoại" class="TTTK_modal_input"
                        style="width: 100%;" required>
                </div>

                <div style="margin-bottom: 15px;">
                    <p class="TTTK_modal_sub" style="margin-bottom: 5px;">Địa chỉ giao hàng</p>
                    <textarea name="dia_chi_day_du"
                        placeholder="Nhập địa chỉ đầy đủ (Số nhà, Đường, Phường/Xã, Quận/Huyện, Tỉnh/Thành phố)..."
                        class="TTTK_modal_input"
                        style="width: 100%; height: 100px; resize: vertical; padding-top: 10px;" required></textarea>
                </div>

                <button type="submit" class="TTTK_btn_submit" style="margin-top: 10px; width: 100%;">HOÀN THÀNH</button>
            </form>

        </div>
    </div>

    <?php include("footer.php"); ?>

    <script>
    // Xử lý chuyển Tab
    function TTTK_switchTab(evt, tabId) {
        var sections = document.getElementsByClassName("TTTK_section");
        for (var i = 0; i < sections.length; i++) {
            sections[i].classList.remove("TTTK_show");
        }
        var menuItems = document.getElementsByClassName("TTTK_menu_item");
        for (var i = 0; i < menuItems.length; i++) {
            menuItems[i].classList.remove("TTTK_active");
        }
        document.getElementById(tabId).classList.add("TTTK_show");
        evt.currentTarget.classList.add("TTTK_active");
    }

    // Xử lý đóng mở Modal Thêm Địa Chỉ
    function TTTK_openModal() {
        document.getElementById('TTTK_AddressModal').style.display = 'flex';
    }

    function TTTK_closeModal() {
        document.getElementById('TTTK_AddressModal').style.display = 'none';
    }

    window.addEventListener('mousedown', function(event) {
        const popup = document.getElementById("TTTK_AddressModal");
        if (event.target == popup) {
            TTTK_closeModal();
        }
    });

    // Hàm kiểm tra định dạng số điện thoại Việt Nam
    function isValidVietnamesePhone(phone) {
        const phoneRegex = /^(84|0[3|5|7|8|9])[0-9]{8}$/;
        return phoneRegex.test(phone);
    }

    // Hàm kiểm tra Họ Tên 
    function isValidName(name) {
        const nameRegex = /^[\p{L}\s]+$/u;
        return nameRegex.test(name);
    }

    // Kiểm tra Form Thông Tin Tài Khoản
    function validateProfileForm() {
        let fullname = document.querySelector('input[name="fullname"]').value.trim();
        let phone = document.querySelector('input[name="phone"]').value.trim();
        let day = document.querySelector('select[name="day"]').value;
        let month = document.querySelector('select[name="month"]').value;
        let year = document.querySelector('select[name="year"]').value;
        let newPassword = document.querySelector('input[name="new_password"]').value.trim();

        // 1. KIỂM TRA HỌ TÊN
        if (fullname === "" || !isValidName(fullname)) {
            alert("Họ tên không hợp lệ! Vui lòng chỉ nhập chữ cái (không bao gồm số hay ký tự đặc biệt như @, #, $...).");
            document.querySelector('input[name="fullname"]').focus();
            return false;
        }

        // 2. KIỂM TRA SỐ ĐIỆN THOẠI
        if (phone !== "" && !isValidVietnamesePhone(phone)) {
            alert("Số điện thoại không hợp lệ! Vui lòng nhập đúng định dạng số điện thoại Việt Nam (10 số).");
            document.querySelector('input[name="phone"]').focus();
            return false;
        }

        // 3. KIỂM TRA NGÀY SINH HỢP LỆ
        if (day !== "" && month !== "" && year !== "") {
            let checkDate = new Date(year, month - 1, day);
            if (checkDate.getFullYear() != year || checkDate.getMonth() + 1 != month || checkDate.getDate() != day) {
                alert("Ngày sinh không hợp lệ! Vui lòng chọn lại (Ví dụ: Tháng 2 không có ngày 30, 31).");
                return false;
            }
        } else if ((day !== "" || month !== "" || year !== "") && !(day !== "" && month !== "" && year !== "")) {
            alert("Vui lòng chọn đầy đủ Ngày, Tháng, Năm sinh hoặc để trống toàn bộ.");
            return false;
        }

        // 4. KIỂM TRA MẬT KHẨU
        if (newPassword !== "") {
            // Ít nhất 8 ký tự, 1 chữ in hoa, 1 ký tự đặc biệt
            const passwordRegex = /^(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}$/;
            if (!passwordRegex.test(newPassword)) {
                alert("Mật khẩu mới phải có ít nhất 8 ký tự, bao gồm ít nhất 1 chữ in hoa và 1 ký tự đặc biệt!");
                document.querySelector('input[name="new_password"]').focus();
                return false;
            }
        }

        return true;
    }

    // Kiểm tra Form Thêm Địa Chỉ Mới
    function validateAddressForm() {
        let phone = document.querySelector('input[name="addr_phone"]').value.trim();
        let address = document.querySelector('textarea[name="dia_chi_day_du"]').value.trim();

        if (!isValidVietnamesePhone(phone)) {
            alert("Số điện thoại giao hàng không hợp lệ! Vui lòng kiểm tra lại.");
            document.querySelector('input[name="addr_phone"]').focus();
            return false;
        }

        if (address.length < 10) {
            alert("Vui lòng nhập địa chỉ đầy đủ và chi tiết hơn (ít nhất 10 ký tự).");
            document.querySelector('textarea[name="dia_chi_day_du"]').focus();
            return false;
        }

        return true;
    }

    // Hàm Xử Lý Xóa Địa Chỉ 
    function xoaDiachi(element) {
        const addressId = $(element).data('id');
        const parentCard = $(element).closest('.address-item');

        if (confirm('Bạn chắc chắn muốn xóa địa chỉ này?')) {
            $.ajax({
                url: 'xuly_diachi.php',
                method: 'POST',
                data: {
                    action: 'xoa',
                    id: addressId
                },
                success: function(response) {
                    if (response.success) {
                        parentCard.fadeOut(300, function() {
                            $(this).remove();
                            // Nếu xóa hết địa chỉ, reload lại trang để hiện câu "Bạn chưa có địa chỉ nào"
                            if ($('.address-item').length === 0) {
                                location.reload();
                            }
                        });
                    } else {
                        alert('Lỗi: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Lỗi AJAX:', error);
                    alert('Có lỗi kết nối, vui lòng thử lại sau.');
                }
            });
        }
    }
    </script>
</body>

</html>