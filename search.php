<?php include("header.php"); ?>

<section class="SP_TrangSanPham">

	<?php
$conn = new mysqli("localhost","root","","GearShop");

if(isset($_GET['keyword'])){
    $keyword = $_GET['keyword'];

    $sql = "SELECT products.*,
                   brands.name AS brand_name,
                   categories.name AS category_name
            FROM products
            JOIN brands ON products.brand_id = brands.id
            JOIN categories ON products.category_id = categories.id
            WHERE products.name LIKE '%$keyword%'";

    $result = $conn->query($sql);
}

if($result && $result->num_rows > 0){
?>

	<div class="SP_DanhSachSanPham">

		<?php while($row = $result->fetch_assoc()){ ?>

		<div class="SP_SanPham">

			<a href="ChiTietSanPham.php?id=<?= $row['id'] ?>">

				<div class="SP_HinhAnh">
					<img src="SanPham/<?= $row['image'] ?>" alt="img">
				</div>

				<h3 class="SP_TenSanPham">
					<?= $row['name'] ?>
				</h3>

				<div class="SP_ThongSo">
					<div>Hãng: <?= $row['brand_name'] ?></div>
					<div>Loại: <?= $row['category_name'] ?></div>
					<div>Còn lại: <?= $row['stock'] ?></div>
				</div>

				<div class="SP_Gia">
					<span class="SP_GiaMoi">
						<?= number_format($row['price'],0,',','.') ?>đ
					</span>
				</div>

				<div class="SP_DanhGia">
					<span class="SP_Sao">★</span> 5.0
					<span class="SP_NhanXet">(100 đánh giá)</span>
				</div>

			</a>

			<a href="addToCart.php?id=<?= $row['id'] ?>">
				<button>Thêm vào giỏ</button>
			</a>

		</div>

		<?php } ?>

	</div>

	<?php
}else{
    include("html/ThongTinSai.html");
}
?>

</section>

<?php include("footer.php"); ?>