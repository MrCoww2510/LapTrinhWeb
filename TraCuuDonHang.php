<?php
require_once("config.php");
include("header.php");

$order_found = false;
$error_msg = "";

// Khi khách bấm nút Tra cứu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tc_ma_don_hang'])) {
    $ma_don_hang_input = trim($_POST['tc_ma_don_hang']);
    $so_dien_thoai = trim($_POST['tc_so_dien_thoai']);

    // Tự động lọc chữ, chỉ giữ lại số. (VD khách nhập "OD15" hoặc "od 15" -> thành số 15)
    $order_id = intval(preg_replace('/[^0-9]/', '', $ma_don_hang_input));

    if ($order_id > 0 && !empty($so_dien_thoai)) {
        // Tìm đơn hàng khớp ID và khớp Số điện thoại của người mua (Chặn không tra cứu giỏ hàng nháp)
        $sql_order = "SELECT o.*, u.phone, u.fullname 
                      FROM orders o 
                      JOIN users u ON o.user_id = u.id 
                      WHERE o.id = $order_id 
                      AND u.phone = '$so_dien_thoai' 
                      AND o.status != 'Cart'";

        $result_order = $Conn->query($sql_order);

        if ($result_order && $result_order->num_rows > 0) {
            $order_info = $result_order->fetch_assoc();
            $order_found = true;

            // Lấy danh sách sản phẩm của đơn hàng này
            $sql_details = "SELECT od.quantity, od.price, p.name 
                            FROM order_details od 
                            JOIN products p ON od.product_id = p.id 
                            WHERE od.order_id = $order_id";
            $result_details = $Conn->query($sql_details);

            // Quy đổi trạng thái Database ra UI
            // 'Pending' (Chờ xử lý), 'Processing' (Đang xử lý), 'Shipping' (Đang giao), 'Delivered' (Đã giao)
            $current_step = 1;
            switch ($order_info['status']) {
                case 'Pending':
                    $current_step = 1;
                    $text_status = "Đã tiếp nhận";
                    break;
                case 'Processing':
                    $current_step = 2;
                    $text_status = "Đang xử lý";
                    break;
                case 'Shipping':
                    $current_step = 3;
                    $text_status = "Đang giao hàng";
                    break;
                case 'Delivered':
                    $current_step = 4;
                    $text_status = "Đã giao thành công";
                    break;
                default:
                    $current_step = 1;
                    $text_status = "Đang cập nhật";
                    break;
            }
        } else {
            $error_msg = "Không tìm thấy đơn hàng! Vui lòng kiểm tra lại Mã đơn hoặc Số điện thoại.";
        }
    } else {
        $error_msg = "Vui lòng nhập định dạng hợp lệ.";
    }
}
?>

<div class="TC_khung_chinh">
    <div class="TC_phan_dau">
        <h1 class="TC_tieu_de">Tra cứu đơn hàng</h1>
        <p class="TC_mo_ta">Nhập mã đơn hàng và số điện thoại để kiểm tra trạng thái vận chuyển.</p>
    </div>

    <div class="TC_phan_tra_cuu">
        <h2 class="TC_tra_cuu_tieu_de">Nhập thông tin</h2>

        <?php if ($error_msg != ""): ?>
            <div style="color: #d9534f; background: #fdf7f7; padding: 10px; border: 1px solid #d9534f; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                <b>Lỗi:</b> <?= $error_msg ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="TC_nhom_nhap">
                <div class="TC_nhap_lieu">
                    <label>Mã đơn hàng</label>
                    <input type="text" name="tc_ma_don_hang"
                        placeholder="Ví dụ: OD15"
                        value="<?= $_POST['tc_ma_don_hang'] ?? '' ?>" required>
                </div>
                <div class="TC_nhap_lieu">
                    <label>Số điện thoại đặt hàng</label>
                    <input type="text" name="tc_so_dien_thoai"
                        placeholder="Ví dụ: 0987654321"
                        value="<?= $_POST['tc_so_dien_thoai'] ?? '' ?>" required>
                </div>
            </div>
            <button type="submit" class="TC_nut_tra_cuu">Tra cứu ngay</button>
        </form>
    </div>

    <?php if ($order_found): ?>
        <div class="TC_phan_ket_qua">
            <h2 class="TC_ket_qua_tieu_de">Kết quả tra cứu</h2>

            <div class="TC_khung_ket_qua">
                <div class="TC_thong_tin_don">
                    <h3 class="TC_thong_tin_tieu_de">Thông tin đơn hàng</h3>
                    <p class="TC_doan_van_ket_qua"><span class="TC_thong_tin_dam_nhat">Mã đơn hàng:</span> OD<?= $order_info['id'] ?></p>
                    <p class="TC_doan_van_ket_qua"><span class="TC_thong_tin_dam_nhat">Khách hàng:</span> <?= $order_info['fullname'] ?></p>
                    <p class="TC_doan_van_ket_qua"><span class="TC_thong_tin_dam_nhat">Số điện thoại:</span> <?= $order_info['phone'] ?></p>
                    <p class="TC_doan_van_ket_qua"><span class="TC_thong_tin_dam_nhat">Trạng thái:</span> <b style="color: #d9534f;"><?= $text_status ?></b></p>
                    <p class="TC_doan_van_ket_qua"><span class="TC_thong_tin_dam_nhat">Ngày đặt:</span> <i>Chưa cập nhật</i></p>
                </div>

                <div class="TC_trang_thai">
                    <h3 class="TC_trang_thai_tieu_de">Tiến trình</h3>
                    <div class="TC_cac_trang_thai_buoc">
                        <div class="TC_buoc <?= ($current_step >= 1) ? 'TC_buoc_hien_tai' : '' ?> <?= ($current_step > 1) ? 'TC_buoc_da_dat' : '' ?>">
                            <div class="TC_vong_tron_trang_thai">1</div>
                            <span class="TC_ten_trang_thai">Đã nhận đơn</span>
                        </div>
                        <div class="TC_buoc <?= ($current_step >= 2) ? 'TC_buoc_hien_tai' : '' ?> <?= ($current_step > 2) ? 'TC_buoc_da_dat' : '' ?>">
                            <div class="TC_vong_tron_trang_thai">2</div>
                            <span class="TC_ten_trang_thai">Đang xử lý</span>
                        </div>
                        <div class="TC_buoc <?= ($current_step >= 3) ? 'TC_buoc_hien_tai' : '' ?> <?= ($current_step > 3) ? 'TC_buoc_da_dat' : '' ?>">
                            <div class="TC_vong_tron_trang_thai">3</div>
                            <span class="TC_ten_trang_thai">Đang giao</span>
                        </div>
                        <div class="TC_buoc <?= ($current_step == 4) ? 'TC_buoc_hien_tai' : '' ?>">
                            <div class="TC_vong_tron_trang_thai">4</div>
                            <span class="TC_ten_trang_thai">Đã giao</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="TC_khung_chi_tiet">
                <h3 class="TC_chi_tiet_tieu_de">Sản phẩm đã mua</h3>
                <ul class="TC_danh_sach_san_pham">
                    <?php while ($item = $result_details->fetch_assoc()): ?>
                        <li class="TC_san_pham_item">
                            <div class="TC_san_pham_info">
                                <p class="TC_ten_san_pham"><?= $item['name'] ?></p>
                                <p class="TC_so_luong">Số lượng: <?= $item['quantity'] ?></p>
                            </div>
                            <span class="TC_gia_san_pham"><?= number_format($item['price'], 0, ',', '.') ?>đ</span>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <div class="TC_tong_cong">
                    Tổng cộng: <span class="TC_tong_tien"><?= number_format($order_info['total_price'], 0, ',', '.') ?>đ</span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include("footer.php"); ?>