<?php
require_once("config.php");

if(!isset($_GET['id'])){
    die("Không có sản phẩm");
}

$id = $_GET['id'];

$sql = "SELECT p.*,
               b.name AS brand_name,
               c.name AS category_name,
               t.cpu, t.ram, t.vga, t.storage, t.screen, t.battery, t.weight
        FROM products p
        JOIN brands b ON p.brand_id = b.id
        JOIN categories c ON p.category_id = c.id
        LEFT JOIN thongso t ON p.id = t.product_id
        WHERE p.id = $id";

$result = $Conn->query($sql); if($result->num_rows == 0){ die("Không tìm thấy
sản phẩm"); } $row = $result->fetch_assoc(); ?>

<div class="CTSP_ChiTietSanPham">
	<!-- LEFT -->
	<div class="CTSP_BenTrai">
		<div class="CTSP_HinhAnh">
			<img id="CTSP_AnhChinh" src="SanPham/<?= $row['image'] ?>" alt="" />
		</div>

		<div class="CTSP_AnhPhu">
			<img src="SanPham/<?= $row['image'] ?>" class="active" />
			<img src="SanPham/<?= $row['image'] ?>" />
			<img src="SanPham/<?= $row['image'] ?>" />
			<img src="SanPham/<?= $row['image'] ?>" />
		</div>
	</div>

	<!-- RIGHT -->
	<div class="CTSP_BenPhai">
		<div class="CTSP_TenSP"><?= $row['name'] ?></div>

		<div class="CTSP_GiaMoi">
			<?= number_format($row['price'],0,',','.') ?>₫
		</div>

		<div class="CTSP_NutMua">
			<a href="addToCart.php?id=<?= $row['id'] ?>">
				<button>MUA NGAY</button>
			</a>
		</div>

		<div class="CTSP_ThongSo">
			<div class="CTSP_ThongSoNB">THÔNG SỐ SẢN PHẨM</div>

			<table>
				<tr>
					<td>Hãng</td>
					<td><?= $row['brand_name'] ?></td>
				</tr>

				<tr>
					<td>Loại</td>
					<td><?= $row['category_name'] ?></td>
				</tr>

				<?php if($row['cpu']){ ?>
				<tr>
					<td>CPU</td>
					<td><?= $row['cpu'] ?></td>
				</tr>
				<?php } ?> <?php if($row['vga']){ ?>
				<tr>
					<td>VGA</td>
					<td><?= $row['vga'] ?></td>
				</tr>
				<?php } ?> <?php if($row['ram']){ ?>
				<tr>
					<td>RAM</td>
					<td><?= $row['ram'] ?></td>
				</tr>
				<?php } ?> <?php if($row['storage']){ ?>
				<tr>
					<td>SSD</td>
					<td><?= $row['storage'] ?></td>
				</tr>
				<?php } ?> <?php if($row['screen']){ ?>
				<tr>
					<td>Màn hình</td>
					<td><?= $row['screen'] ?></td>
				</tr>
				<?php } ?> <?php if($row['battery']){ ?>
				<tr>
					<td>Pin</td>
					<td><?= $row['battery'] ?></td>
				</tr>
				<?php } ?> <?php if($row['weight']){ ?>
				<tr>
					<td>Trọng lượng</td>
					<td><?= $row['weight'] ?></td>
				</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>
<script>
const anhChinh = document.getElementById("CTSP_AnhChinh");
const dsAnh = document.querySelectorAll(".CTSP_AnhPhu img");

dsAnh.forEach(function(img) {
	img.addEventListener("click", function() {
		anhChinh.src = img.src;

		dsAnh.forEach(function(i) {
			i.classList.remove("active");
		});

		img.classList.add("active");
	});
});
</script>