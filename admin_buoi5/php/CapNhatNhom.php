<div class="Content">
    <div class="TN_card-container">
        <div class="TN_card-header">
            Cập nhật nhóm
        </div>
        
        <form method="POST" action="">
            <div class="TN_card-body">

                <!-- Tên nhóm -->
                <div class="TN_form-group">
                    <label for="tenNhom">Tên nhóm:</label>
                    <input type="text" id="tenNhom" name="tenNhom" required>
                </div>

                <!-- Thứ tự -->
                <div class="TN_form-group">
                    <label for="thuTu">Thứ tự:</label>
                    <input type="number" id="thuTu" name="thuTu"
                           value="<?php echo $nhom['thuTu']; ?>" required>
                </div>

            </div>

            <div class="TN_card-footer">
                <button type="submit" class="TN_btn-submit">CẬP NHẬT</button>
            </div>
        </form>
    </div>
</div>