<?php
// controllers/ProductController.php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Store.php';

class ProductController
{
    public function index(): void
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['db_user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $filters = [
            'keyword'  => trim($_GET['q'] ?? ''),
            'store_id' => $_GET['store_id'] ?? '',
            'sort'     => $_GET['sort'] ?? 'name_asc',
        ];

        $stores   = Store::getAll();
        $products = Product::getAll($filters);

        require __DIR__ . '/../views/layout/header.php';
        require __DIR__ . '/../views/products/index.php';
        require __DIR__ . '/../views/layout/footer.php';
    }

    public function form(): void
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['db_user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $isEdit = $id > 0;
        $stores = Store::getAll();
        $errors = [];

        if ($isEdit) {
            $product = Product::find($id);
            if (!$product) {
                die('Không tìm thấy sản phẩm.');
            }
        } else {
            $product = [
                'Product_ID'     => '',
                'Store_ID'       => '',
                'Name'           => '',
                'Description'    => '',
                'Price'          => '',
                'Images_url'     => '',
                'Stock_quantity' => 0,
                'Status'         => 'Available',
            ];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product['Store_ID']       = (int)($_POST['Store_ID'] ?? 0);
            $product['Name']           = trim($_POST['Name'] ?? '');
            $product['Description']    = trim($_POST['Description'] ?? '');
            $product['Price']          = (float)($_POST['Price'] ?? 0);
            $product['Images_url']     = trim($_POST['Images_url'] ?? '');
            $product['Stock_quantity'] = (int)($_POST['Stock_quantity'] ?? 0);
            $product['Status']         = $_POST['Status'] ?? 'Available';

            $errors = Product::validate($product);

            if (!$errors) {
                if ($isEdit) {
                    Product::update($id, $product);
                } else {
                    Product::create($product);
                }
                header('Location: index.php?page=products');
                exit;
            }
        }

        require __DIR__ . '/../views/layout/header.php';
        require __DIR__ . '/../views/products/form.php';
        require __DIR__ . '/../views/layout/footer.php';
    }

    public function delete(): void
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['db_user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            Product::delete($id);
        }
        header('Location: index.php?page=products');
        exit;
    }
}
