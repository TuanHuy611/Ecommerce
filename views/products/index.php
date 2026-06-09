<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Danh sách sản phẩm</h3>
    <a href="index.php?page=product_form" class="btn btn-success">
        + Thêm sản phẩm
    </a>
</div>

<form class="row g-2 mb-4" method="get">
    <input type="hidden" name="page" value="products">
    <div class="col-md-4">
        <input type="text" class="form-control" name="q"
               placeholder="Tìm theo tên sản phẩm..."
               value="<?= htmlspecialchars($filters['keyword']) ?>">
    </div>
    <div class="col-md-3">
        <select class="form-select" name="store_id">
            <option value="">-- Tất cả cửa hàng --</option>
            <?php foreach ($stores as $s): ?>
                <option value="<?= $s['Store_ID'] ?>"
                    <?= ($filters['store_id'] == $s['Store_ID']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['Name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <select class="form-select" name="sort">
            <option value="name_asc"  <?= $filters['sort'] === 'name_asc' ? 'selected' : '' ?>>Tên A → Z</option>
            <option value="name_desc" <?= $filters['sort'] === 'name_desc' ? 'selected' : '' ?>>Tên Z → A</option>
            <option value="price_asc" <?= $filters['sort'] === 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
            <option value="price_desc"<?= $filters['sort'] === 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
        </select>
    </div>
    <div class="col-md-2 d-grid">
        <button class="btn btn-outline-primary" type="submit">Lọc / Sắp xếp</button>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Cửa hàng</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Trạng thái</th>
                    <th class="text-end">Thao tác</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($products)): ?>
                    <tr><td colspan="7" class="text-center py-3">Không có sản phẩm phù hợp.</td></tr>
                <?php else: foreach ($products as $p): ?>
                    <tr>
                        <td><?= $p['Product_ID'] ?></td>
                        <td><?= htmlspecialchars($p['Name']) ?></td>
                        <td><?= htmlspecialchars($p['StoreName']) ?></td>
                        <td><?= number_format($p['Price'], 0, ',', '.') ?> đ</td>
                        <td><?= $p['Stock_quantity'] ?></td>
                        <td><?= htmlspecialchars($p['Status']) ?></td>
                        <td class="text-end">
                            <a href="index.php?page=product_form&id=<?= $p['Product_ID'] ?>"
                               class="btn btn-sm btn-outline-primary">Sửa</a>
                            <a href="index.php?page=product_delete&id=<?= $p['Product_ID'] ?>"
                               class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Xóa sản phẩm này?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
