<link rel="stylesheet" href="ThemSanPham.css">
<?php
if(isset($_POST['them'])){
    $ten = $_POST['ten'];
    $gia = $_POST['gia'];
    $thongso = $_POST['thongso'];

    $hinhanh = $_FILES['hinhanh']['name'];
    $tmp = $_FILES['hinhanh']['tmp_name'];

    move_uploaded_file($tmp, "uploads/".$hinhanh);

    echo "<script>alert('Thêm sản phẩm thành công!');</script>";
}
?>

<div class="container">
	<h2>THÊM SẢN PHẨM</h2>

	<form method="POST" enctype="multipart/form-data">

		<!-- Ảnh -->
		<div class="form-group">
			<label>Hình ảnh</label>
			<input type="file" name="hinhanh" id="fileInput" required>
			<img id="preview" src="" alt="">
		</div>

		<!-- Tên -->
		<div class="form-group">
			<label>Tên sản phẩm</label>
			<input type="text" name="ten" required>
		</div>

		<!-- Giá -->
		<div class="form-group">
			<label>Giá</label>
			<input type="number" name="gia" required>
		</div>

		<!-- Thông số -->
		<div class="form-group">
			<label>Thông số</label>
			<textarea name="thongso" rows="4"></textarea>
		</div>

		<button type="submit" name="them">Thêm sản phẩm</button>
	</form>
</div>

<script>
document.getElementById("fileInput").onchange = function(e) {
	const [file] = e.target.files;
	if (file) {
		let img = document.getElementById("preview");
		img.src = URL.createObjectURL(file);
		img.style.display = "block";
	}
}
</script>

</body>

</html>