<div class="row">
    <div class="col-md-5">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5 class="card-title mb-3">Thống kê doanh thu tháng</h5>
                <form method="post">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Năm</label>
                            <input type="number" class="form-control" name="year"
                                   value="<?= $year ?>" min="2000" max="2100">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tháng</label>
                            <input type="number" class="form-control" name="month"
                                   value="<?= $month ?>" min="1" max="12">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Xem thống kê</button>
                </form>
            </div>
        </div>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </div>

    <div class="col-md-7">
        <?php if ($result): ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        Kết quả tháng <?= htmlspecialchars($result['month']) ?>/<?= htmlspecialchars($result['year']) ?>
                    </h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Tổng tiền sản phẩm:</span>
                            <strong><?= number_format($result['total_product_amount'], 0, ',', '.') ?> đ</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Phí ship - Giảm giá:</span>
                            <strong><?= number_format($result['total_extra_fee'], 0, ',', '.') ?> đ</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><u>Tổng doanh thu:</u></span>
                            <strong><?= number_format($result['total_revenue'], 0, ',', '.') ?> đ</strong>
                        </li>
                    </ul>
                </div>
            </div>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error): ?>
            <div class="alert alert-info">
                Không có đơn hàng nào trong tháng này.
            </div>
        <?php else: ?>
            <p class="text-muted">Vui lòng chọn năm & tháng rồi bấm “Xem thống kê”.</p>
        <?php endif; ?>
    </div>
</div>
