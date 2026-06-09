<?php
// index.php
session_start();

$page = $_GET['page'] ?? 'products';

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ProductController.php';
require_once __DIR__ . '/controllers/StatsController.php';

switch ($page) {
    case 'login':
        (new AuthController())->login();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;
    case 'products':
        (new ProductController())->index();
        break;
    case 'product_form':
        (new ProductController())->form();
        break;
    case 'product_delete':
        (new ProductController())->delete();
        break;
    case 'stats':
        (new StatsController())->index();
        break;
    default:
        header('Location: index.php?page=products');
        exit;
}
