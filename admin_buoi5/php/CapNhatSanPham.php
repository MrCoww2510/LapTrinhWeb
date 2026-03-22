<div class="Content">
    <div class="TN_card-container">
        <div class="TN_card-header">
            Cập nhật sản phẩm
        </div>
        
        <form method="POST" action="">
            <div class="TN_card-body">

                <!-- Tên sản phẩm -->
                <div class="TN_form-group">
                    <label for="tenSanPham">Tên sản phẩm:</label>
                    <input type="text" id="tenSanPham" name="tenSanPham" required>
                </div>

                <!-- Giá -->
                <div class="TN_form-group">
                    <label for="gia">Giá:</label>
                    <input type="number" id="gia" name="gia" 
                           value="<?php echo $sanpham['gia']; ?>" required>
                </div>

                <!-- Số lượng -->
                <div class="TN_form-group">
                    <label for="soLuong">Số lượng:</label>
                    <input type="number" id="soLuong" name="soLuong" 
                           value="<?php echo $sanpham['soLuong']; ?>" required>
                </div>

                <!-- Nhóm sản phẩm -->
                <div class="TN_form-group">
                    <label for="nhom">Nhóm sản phẩm:</label>
                    <select name="nhom" id="nhom">
                        <?php foreach($dsNhom as $nhom){ ?>
                            <option value="<?php echo $nhom['id']; ?>"
                                <?php if($nhom['id'] == $sanpham['nhom_id']) echo "selected"; ?>>
                                <?php echo $nhom['tenNhom']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Mô tả -->
                <div class="TN_form-group">
                    <label for="moTa">Mô tả:</label>
                    <textarea id="moTa" name="moTa"></textarea>
                </div>

            </div>

            <div class="TN_card-footer">
                <button type="submit" class="TN_btn-submit">CẬP NHẬT</button>
            </div>
        </form>
    </div>
</div>