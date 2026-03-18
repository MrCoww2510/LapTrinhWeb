<link rel="stylesheet" href="css/ThongTinTaiKhoan.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php include("header.php"); ?>

<div class="TTTK_container">
    <aside class="TTTK_sidebar">
        <div class="TTTK_user_info">
            <div class="TTTK_avatar"><i class="fas fa-user"></i></div>
            <span class="TTTK_username">Toàn</span>
        </div>
        <ul class="TTTK_menu_list">
            <li class="TTTK_menu_item TTTK_active" onclick="TTTK_switchTab(event, 'tab_profile')">
                <i class="fas fa-user-circle"></i> Thông tin tài khoản
            </li>
            <li class="TTTK_menu_item" onclick="TTTK_switchTab(event, 'tab_address')">
                <i class="fas fa-map-marker-alt"></i> Sổ địa chỉ
            </li>
            
            <li class="TTTK_menu_item"><i class="fas fa-sign-out-alt"></i> Đăng xuất</li>
        </ul>
    </aside>

    <main class="TTTK_main_content">
        
        <section id="tab_profile" class="TTTK_section TTTK_show">
            <h2 class="TTTK_title">Thông tin tài khoản</h2>
            <form action="" method="POST" style="margin-top: 30px;">
                <div class="TTTK_form_group">
                    <label class="TTTK_label">Họ Tên</label>
                    <input type="text" name="fullname" class="TTTK_input" value="Toàn">
                </div>
                
                <div class="TTTK_form_group">
                    <label class="TTTK_label">Giới tính</label>
                    <div class="TTTK_radio_group">
                        <label><input type="radio" name="gender" value="Nam" checked> Nam</label>
                        <label><input type="radio" name="gender" value="Nữ"> Nữ</label>
                    </div>
                </div>

                <div class="TTTK_form_group">
                    <label class="TTTK_label">Số điện thoại</label>
                    <input type="tel" name="phone" class="TTTK_input" placeholder="Nhập số điện thoại">
                </div>

                <div class="TTTK_form_group">
                    <label class="TTTK_label">Email</label>
                    <input type="email" name="email" class="TTTK_input" value="toanyena22@gmail.com">
                </div>

                <div class="TTTK_form_group">
                    <label class="TTTK_label">Ngày sinh</label>
                    <div class="TTTK_select_group">
                        <select name="day" class="TTTK_select">
                            <option value="">Ngày</option>
                            <?php for($i=1;$i<=31;$i++) echo "<option value='$i'>$i</option>"; ?>
                        </select>
                        <select name="month" class="TTTK_select">
                            <option value="">Tháng</option>
                            <?php for($i=1;$i<=12;$i++) echo "<option value='$i'>Tháng $i</option>"; ?>
                        </select>
                        <select name="year" class="TTTK_select">
                            <option value="">Năm</option>
                            <?php for($i=2026;$i>=1950;$i--) echo "<option value='$i'>$i</option>"; ?>
                        </select>
                    </div>
                </div>

                <button type="submit" class="TTTK_btn_save">LƯU THAY ĐỔI</button>
            </form>
        </section>

        <section id="tab_address" class="TTTK_section">
            <div class="TTTK_header_row">
                <h2 class="TTTK_title">Thông tin tài khoản</h2>
                <button type="button" class="TTTK_btn_add_addr" onclick="TTTK_openModal()">+ Thêm địa chỉ mới</button>
            </div>
            <div style="color: #999; margin-top: 50px; text-align: center;">
                <p>Bạn chưa có địa chỉ nào trong sổ địa chỉ.</p>
            </div>
        </section>

    </main>
</div>

<div class="TTTK_modal_overlay" id="TTTK_AddressModal">
    <div class="TTTK_modal_box">
        <div class="TTTK_modal_header">
            <span>ĐỊA CHỈ MỚI</span>
            <span style="cursor:pointer; font-size:20px;" onclick="TTTK_closeModal()">&times;</span>
        </div>
        <form class="TTTK_modal_body" action="" method="POST">
            <p class="TTTK_modal_sub">Thông tin khách hàng</p>
            <input type="text" placeholder="Nhập Họ Tên" class="TTTK_modal_input" required>
            <input type="tel" placeholder="Nhập Số điện thoại" class="TTTK_modal_input" required>

            <p class="TTTK_modal_sub">Địa chỉ</p>
            <div class="TTTK_modal_row">
                <select class="TTTK_modal_input"><option>Chọn Tỉnh/Thành phố</option></select>
                <select class="TTTK_modal_input"><option>Chọn Quận/Huyện</option></select>
            </div>
            <div class="TTTK_modal_row">
                <select class="TTTK_modal_input"><option>Chọn Phường/Xã</option></select>
                <input type="text" placeholder="Số nhà, địa chỉ" class="TTTK_modal_input">
            </div>

            <p class="TTTK_modal_sub">Loại địa chỉ</p>
            <div class="TTTK_type_group">
                <label style="flex:1;"><input type="radio" name="addr_type" value="Văn phòng" style="display:none;" checked><div class="TTTK_type_label">Văn phòng</div></label>
                <label style="flex:1;"><input type="radio" name="addr_type" value="Nhà riêng" style="display:none;"><div class="TTTK_type_label">Nhà riêng</div></label>
            </div>
            <button type="submit" class="TTTK_btn_submit">HOÀN THÀNH</button>
        </form>
    </div>
</div>

<?php include("footer.php"); ?>

<script>
    function TTTK_switchTab(evt, tabId) {
        var sections = document.getElementsByClassName("TTTK_section");
        for (var i = 0; i < sections.length; i++) { sections[i].classList.remove("TTTK_show"); }
        var menuItems = document.getElementsByClassName("TTTK_menu_item");
        for (var i = 0; i < menuItems.length; i++) { menuItems[i].classList.remove("TTTK_active"); }
        document.getElementById(tabId).classList.add("TTTK_show");
        evt.currentTarget.classList.add("TTTK_active");
    }

    function TTTK_openModal() { document.getElementById('TTTK_AddressModal').style.display = 'flex'; }
    function TTTK_closeModal() { document.getElementById('TTTK_AddressModal').style.display = 'none'; }
    window.onclick = function(e) { if(e.target == document.getElementById('TTTK_AddressModal')) TTTK_closeModal(); }
</script>