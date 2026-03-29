<?php
session_start();
require_once("config.php");

// Kiểm tra đăng nhập (bắt buộc phải có để biết giỏ hàng của ai)
if (!KiemTraDangNhap()) {
    echo "<script>alert('Vui lòng đăng nhập để xem giỏ hàng!'); window.location.href='DangNhap.php';</script>";
    exit();
}

$user_id = intval($_SESSION['user']['id']);

// Lấy dữ liệu giỏ hàng từ Database
$sql_cart = "SELECT od.id AS detail_id, p.name, p.image, p.price, od.quantity
             FROM order_details od
             JOIN orders o ON od.order_id = o.id
             JOIN products p ON od.product_id = p.id
             WHERE o.user_id = $user_id AND o.status = 'Cart'";
$result_cart = $Conn->query($sql_cart);
$grand_total = 0;

include("header.php");
?>

<section class="GH_container">
	<h2 class="GH_title">🛒 Giỏ hàng của bạn</h2>

	<table class="GH_table">
		<thead>
			<tr>
				<th>Hình</th>
				<th>Tên sản phẩm</th>
				<th>Giá</th>
				<th>Số lượng</th>
				<th>Tổng</th>
				<th>Xóa</th>
			</tr>
		</thead>

		<tbody>
			<?php
            // Nếu có sản phẩm trong giỏ thì lặp để in ra
            if ($result_cart && $result_cart->num_rows > 0):
                while ($row = $result_cart->fetch_assoc()):
                    $subtotal = $row['price'] * $row['quantity'];
                    $grand_total += $subtotal;
            ?>

			<tr class="GH_item" data-price="<?= $row['price'] ?>">
				<td>
					<img src="SanPham/<?= $row['image'] ?>" alt="" width="80" />
				</td>
				<td><?= $row['name'] ?></td>
				<td><?= number_format($row['price'], 0, ',', '.') ?>đ</td>
				<td>
					<input type="number" value="<?= $row['quantity'] ?>" min="1" class="GH_quantity" />
				</td>
				<td class="GH_item_subtotal"><?= number_format($subtotal, 0, ',', '.') ?>đ</td>
				<td>
					<a href="xoa_gio_hang.php?id=<?= $row['detail_id'] ?>">
						<button type="button" class="GH_delete" style="cursor: pointer;">❌</button>
					</a>
				</td>
			</tr>

			<?php
                endwhile;
            else:
                ?>
			<tr>
				<td colspan="6" style="text-align: center; padding: 30px; font-weight: bold;">
					Giỏ hàng của bạn đang trống!
				</td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>

	<div class="GH_totalBox">
		<h3>Tổng tiền: <span id="GH_grand_total"><?= number_format($grand_total, 0, ',', '.') ?>đ</span></h3>

		<div class="GH_actions">
			<button class="GH_continue" onclick="window.location.href='TrangSanPham.php'">← Tiếp tục mua</button>
			<button class="GH_checkout" onclick="moPopupThanhToan()">Thanh toán →</button>
		</div>
	</div>
</section>

<script>
function formatCurrency(number) {
	return new Intl.NumberFormat('vi-VN').format(number) + 'đ';
}

function updateCart() {
	let grandTotal = 0;
	const cartItems = document.querySelectorAll('.GH_item');

	cartItems.forEach(item => {
		const price = parseFloat(item.getAttribute('data-price'));
		const quantityInput = item.querySelector('.GH_quantity');
		let quantity = parseInt(quantityInput.value);

		if (quantity < 1 || isNaN(quantity)) {
			quantity = 1;
			quantityInput.value = 1;
		}

		const subtotal = price * quantity;
		grandTotal += subtotal;
		item.querySelector('.GH_item_subtotal').innerText = formatCurrency(subtotal);
	});

	document.getElementById('GH_grand_total').innerText = formatCurrency(grandTotal);
}

document.querySelectorAll('.GH_quantity').forEach(input => {
	input.addEventListener('input', updateCart);
	input.addEventListener('change', updateCart);
});
</script>

<div id="popupThanhToan" class="GH_modal">
	<div class="GH_modal_content">
		<div class="GH_modal_header">
			Xác nhận đơn hàng - F1GamingGear
		</div>

		<div class="GH_modal_body">
			<p><strong>Khách hàng:</strong> <?= $_SESSION['user']['fullname'] ?? 'Chưa cập nhật' ?></p>
			<hr style="border: 0.5px solid #eee; margin: 15px 0;">

			<p><strong>Sản phẩm trong giỏ:</strong></p>
			<ul>
				<?php
                // Reset lại con trỏ dữ liệu để vòng lặp có thể chạy lại lần 2 lấy tên sản phẩm
                if (isset($result_cart) && $result_cart->num_rows > 0) {
                    mysqli_data_seek($result_cart, 0);
                    while ($item = $result_cart->fetch_assoc()) {
                        echo "<li>" . $item['name'] . " <b>(x" . $item['quantity'] . ")</b></li>";
                    }
                }
                ?>
			</ul>

			<hr style="border: 0.5px solid #eee; margin: 15px 0;">
			<h3 style="color: red; text-align: right; margin: 0;">
				Tổng thanh toán: <?= number_format($grand_total, 0, ',', '.') ?>đ
			</h3>
		</div>

		<div class="GH_modal_footer">
			<button class="btn-huy" onclick="dongPopupThanhToan()">Hủy bỏ</button>
			<button class="btn-xac-nhan" onclick="window.location.href='TrangThanhToan.php'">Xác nhận</button>
		</div>
	</div>
</div>



<script>
function moPopupThanhToan() {
	document.getElementById('popupThanhToan').style.display = 'block';
}

function dongPopupThanhToan() {
	document.getElementById('popupThanhToan').style.display = 'none';
}
</script>

<?php include("footer.php");
?>