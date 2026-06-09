<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title mb-3">
                    <?= $isEdit ? 'Cập nhật sản phẩm' : 'Thêm sản phẩm mới' ?>
                </h4>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $e): ?>
                                <li><?= htmlspecialchars($e) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Cửa hàng</label>
                            <select name="Store_ID" class="form-select" required>
                                <option value="">-- Chọn cửa hàng --</option>
                                <?php foreach ($stores as $s): ?>
                                    <option value="<?= $s['Store_ID'] ?>"
                                        <?= ($product['Store_ID'] == $s['Store_ID']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($s['Name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Trạng thái</label>
                            <select name="Status" class="form-select">
                                <option value="Available"    <?= $product['Status']=='Available'    ? 'selected' : '' ?>>Available</option>
                                <option value="Out of Stock" <?= $product['Status']=='Out of Stock' ? 'selected' : '' ?>>Out of Stock</option>
                                <option value="Discontinued" <?= $product['Status']=='Discontinued'? 'selected' : '' ?>>Discontinued</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tên sản phẩm</label>
                        <input type="text" class="form-control" name="Name"
                               value="<?= htmlspecialchars($product['Name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="Description" rows="3"><?= htmlspecialchars($product['Description']) ?></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Giá (VNĐ)</label>
                            <input type="number" class="form-control" name="Price"
                                   value="<?= htmlspecialchars($product['Price']) ?>" min="0" step="1000" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tồn kho</label>
                            <input type="number" class="form-control" name="Stock_quantity"
                                   value="<?= htmlspecialchars($product['Stock_quantity']) ?>" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ảnh (URL)</label>
                            <input type="text" class="form-control" name="Images_url"
                                   value="<?= htmlspecialchars($product['Images_url']) ?>">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?page=products" class="btn btn-outline-secondary">Quay lại</a>
                        <button type="submit" class="btn btn-primary">
                            <?= $isEdit ? 'Lưu thay đổi' : 'Thêm sản phẩm' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
