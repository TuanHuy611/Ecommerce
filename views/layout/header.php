<?php if (!isset($_SESSION)) session_start(); ?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Ecommerce Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Ecommerce Manager</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php if (isset($_SESSION['db_user'])): ?>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= ($_GET['page'] ?? 'products') === 'products' ? 'active' : '' ?>"
                           href="index.php?page=products">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($_GET['page'] ?? '') === 'stats' ? 'active' : '' ?>"
                           href="index.php?page=stats">Thống kê doanh thu</a>
                    </li>
                </ul>
                <span class="navbar-text me-3">
                    Đăng nhập: <strong><?= htmlspecialchars($_SESSION['db_user']) ?></strong>
                </span>
                <a class="btn btn-outline-light btn-sm" href="index.php?page=logout">Đăng xuất</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container mb-5">
