<footer class="FT_footer">
	<div class="FT_container">
		<!-- ===== CỘT 1 ===== -->
		<div class="FT_col">
			<h3 class="FT_title">F1GamingGear</h3>
			<p class="FT_text">
				Chuyên cung cấp thiết bị gaming chính hãng như bàn phím, chuột, tai nghe và laptop gaming.
			</p>
		</div>
		<!-- ===== CỘT 2 ===== -->
		<div class="FT_col">
			<h3 class="FT_title">Liên kết nhanh</h3>
			<ul class="FT_list">
				<li><a href="index.php">Trang chủ</a></li>
				<li><a href="TrangSanPham.php">Sản phẩm</a></li>
				<li><a href="TrangGioiThieu.php">Giới thiệu</a></li>
				<li><a href="TrangBaoHanh.php">Bảo hành</a></li>
			</ul>
		</div>
		<!-- ===== CỘT 3 ===== -->
		<div class="FT_col">
			<h3 class="FT_title">Hỗ trợ</h3>
			<ul class="FT_list">
				<li><a href="#">Chính sách bảo hành</a></li>
				<li><a href="#">Chính sách đổi trả</a></li>
				<li><a href="#">Hướng dẫn mua hàng</a></li>
				<li><a href="#">Liên hệ</a></li>
			</ul>
		</div>
		<!-- ===== CỘT 4 ===== -->
		<div class="FT_col">
			<h3 class="FT_title">Liên hệ</h3>
			<p class="FT_text">📍 Cần Thơ, Việt Nam</p>
			<p class="FT_text">📞 0123 456 789</p>
			<p class="FT_text">📧 support@f1gaminggear.vn</p>
		</div>
	</div>
	<!-- ===== COPYRIGHT ===== -->
	<div class="FT_bottom">
		<p>© 2026 F1GamingGear - Nhóm 10 </p>
	</div>
</footer>
<button onclick="scrollToTop()" id="backToTop">↑</button>
<script>
let backToTop = document.getElementById("backToTop");

window.onscroll = function() {
	if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
		backToTop.style.display = "block";
	} else {
		backToTop.style.display = "none";
	}
};

function scrollToTop() {
	window.scrollTo({
		top: 0,
		behavior: "smooth"
	});
}
</script>
</body>

</html>